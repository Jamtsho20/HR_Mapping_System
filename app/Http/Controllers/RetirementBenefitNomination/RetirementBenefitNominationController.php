<?php

namespace App\Http\Controllers\RetirementBenefitNomination;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\MasRetirementBenefitTypes;
use App\Models\RetirementBenefit;
use App\Models\RetirementBenefitDetail;
use App\Models\RetirementBenefitNomination;
use App\Models\User;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RetirementBenefitNominationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-nomination,view')->only('index');
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-nomination,create')->only('store');
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-nomination,edit')->only('update');
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-nomination,delete')->only('destroy');
    }
    private $filePath = 'images/retirement/';

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $retirementNomination = RetirementBenefit::with('employee.empJob.designation', 'employee.empJob.section', 'employee.empJob.department')
            ->where('mas_employee_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
        $latestStatus = $retirementNomination->first()?->status;
        return view('retirement-benefit-nomination.retirement-benefit-nomination.index', compact('privileges', 'retirementNomination', 'latestStatus'));
    }
    public function create(Request $request)
    {
        $user = auth()->user();

        return view('retirement-benefit-nomination.retirement-benefit-nomination.create', compact('user'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $retirementTypeId = MasRetirementBenefitTypes::first()->id;

        $conditionFields = approvalHeadConditionFields(RETIREMENT_BENEFIT_NOM, $request);
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($retirementTypeId, \App\Models\MasRetirementBenefitTypes::class, $conditionFields ?? []);

        $request->validate([
            'employee_id' => 'required|exists:mas_employees,id', // Ensure an employee is selected
            'remarks' => 'nullable|string',

            'retirement_benefit' => 'required|array|min:1', // At least one nomination should be provided
            'retirement_benefit.*.nominee_name' => 'required|string|max:255',
            'retirement_benefit.*.relation_with_employee' => 'required|string|max:255',
            'retirement_benefit.*.cid_number' => 'required|string|max:11',
            'retirement_benefit.*.percentage_of_share' => 'required|numeric|min:1|max:100',
            'retirement_benefit.*.attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $nomination = new RetirementBenefit();
            $nomination->mas_employee_id = $request->employee_id;
            $nomination->benefit_type_id = $retirementTypeId;
            $nomination->remarks = $request->remarks;
            $nomination->status = 1;
            $nomination->save();

            foreach ($request->retirement_benefit as $key => $data) {
                $detail = new RetirementBenefitDetail();
                $detail->retirement_benefit_id = $nomination->id;
                $detail->nominee_name = $data['nominee_name'];
                $detail->relation_with_employee = $data['relation_with_employee'];
                $detail->cid_number = $data['cid_number'];
                $detail->percentage_of_share = $data['percentage_of_share'];

                if (isset($data['attachment']) && $data['attachment']->isValid()) {
                    $detail->attachment = uploadImageToDirectory($data['attachment'], $this->filePath);
                }

                $detail->save();
            }

            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($nomination->histories(), $approverByHierarchy, $request->remarks);
            DB::commit();
            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has applied for Retirement Benefit Nomination and forwarded it for your endorsement.';
                $emailSubject = 'Advance';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
            }
            return redirect()->route('retirement-benefit-nomination.index')
                ->with('msg_success', 'Retirement benefit nomination submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $user = auth()->user();
        $nomination = RetirementBenefit::with([
            'employee.empJob.designation',
            'employee.empJob.section',
            'employee.empJob.department',
            'details'
        ])->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\AdvanceApplication::class, $nomination->id);

        return view('retirement-benefit-nomination.retirement-benefit-nomination.show', compact('nomination', 'user', 'approvalDetail'));
    }

    public function edit(string $id)
    {
        $user = auth()->user();
        $retirementNomination  = RetirementBenefit::with('details', 'employee.empJob.designation', 'employee.empJob.section', 'employee.empJob.department')
            ->findOrFail($id);

        return view('retirement-benefit-nomination.retirement-benefit-nomination.edit', compact('retirementNomination', 'user'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'retirement_benefit' => 'required|array|min:1',
            'retirement_benefit.*.nominee_name' => 'required|string|max:255',
            'retirement_benefit.*.relation_with_employee' => 'required|string|max:255',
            'retirement_benefit.*.cid_number' => 'required|string|max:11',
            'retirement_benefit.*.percentage_of_share' => 'required|numeric|min:1|max:100',
            'retirement_benefit.*.attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $nomination = RetirementBenefit::findOrFail($id);
            $nomination->remarks = $request->remarks;
            $nomination->status = $request->status ?? $nomination->status;
            $nomination->updated_by = auth()->id();
            $nomination->has_been_edited = true;
            $nomination->save();
            // Notify approver
            $approver = User::whereHas('roles', function ($q) {
                $q->where('role_id', SIFA_MANAGER);
            })->first();
            // dd($approver);
            Mail::to($approver->email)->send(new \App\Mail\RetirementEditedNotificationMail($nomination, $approver->id));
            // Delete removed details first
            $existingIds = collect($request->retirement_benefit)->pluck('id')->filter()->toArray();
            RetirementBenefitDetail::where('retirement_benefit_id', $nomination->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            foreach ($request->retirement_benefit as $data) {
                $detail = isset($data['id'])
                    ? RetirementBenefitDetail::findOrFail($data['id'])
                    : new RetirementBenefitDetail();

                $detail->retirement_benefit_id = $nomination->id;
                $detail->nominee_name = $data['nominee_name'];
                $detail->relation_with_employee = $data['relation_with_employee'];
                $detail->cid_number = $data['cid_number'];
                $detail->percentage_of_share = $data['percentage_of_share'];

                if (isset($data['attachment']) && $data['attachment']->isValid()) {
                    $detail->attachment = uploadImageToDirectory($data['attachment'], $this->filePath);
                }

                $detail->save();
            }

            DB::commit();

            return redirect()->route('retirement-benefit-nomination.index')
                ->with('msg_success', 'Retirement benefit nomination updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function destroy(string $id)
    { {
            try {
                // Attempt to find and delete the Retirement Benefit
                RetirementBenefit::findOrFail($id)->delete();
                // Redirect back with a success message
                return back()->with('msg_success', 'Retirement Benefit has been deleted');
            } catch (\Exception $e) {
                // Handle the exception, typically due to foreign key constraints
                return back()->with('msg_error', 'Retirement Benefit cannot be deleted as it has been used by another module. For further information, contact the system admin.');
            }
        }
    }
}
