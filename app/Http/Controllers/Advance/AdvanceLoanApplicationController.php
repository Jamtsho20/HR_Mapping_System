<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\AjaxRequestController;
use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\AdvanceApplication;
use App\Models\AdvanceDetail;
use App\Models\BudgetCode;
use App\Models\InterestRate;
use App\Models\LoanEMIDeduction;
use App\Models\MasAdvanceTypes;
use App\Models\MasDzongkhag;
use App\Models\TravelAuthorizationApplication;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdvanceLoanApplicationController extends Controller
{
    protected $ajax;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(AjaxRequestController $ajax)
    {
        $this->ajax = $ajax;
        $this->middleware('permission:advance-loan/apply,view')->only('index', 'show');
        $this->middleware('permission:advance-loan/apply,create')->only('store');
        $this->middleware('permission:advance-loan/apply,edit')->only('update');
        $this->middleware('permission:advance-loan/apply,delete')->only('destroy');
    }

    private $attachmentPath = 'images/advance/';

    protected $rules = [
        'date' => 'required|date',
        'advance_type' => 'required',
        'transaction_no' => 'required_if:advance_type,' . DSA_ADVANCE,
        'advance_settlement_date' => 'required_if:advance_type,' . ADVANCE_TO_STAFF,
        'item_type' => 'required_if:advance_type,' . GADGET_EMI,
        'amount' => '|required_if:advance_type,' . DSA_ADVANCE . '|required_if:advance_type,' . ADVANCE_TO_STAFF .
            '|required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . IMPREST_ADVANCE . '|required_if:advance_type,' . SALARY_ADVANCE . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'interest_rate' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'total_amount' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'no_of_emi' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN,
        'monthly_emi_amount' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN,
        'deduction_from_period' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|date_format:Y-m',
    ];

    protected $messages = [
        'transaction_no.unique' => 'Advance Number has already been taken, please refresh the page and try again.',
        'transaction_no.required_if' => 'Travel authorization no is required for the selected advance type.',
        'advance_settlement_date.required_if' => 'Advance settlement date no is required for the selected advance type.',
        'item_type.required_if' => 'Item type is required for the selected gadget EMI.',
        'amount.required_if' => 'Amount is required for the selected advance type.',
        'interest_rate.required_if' => 'Interest rate is required for the selected advance type.',
        'total_amount.required_if' => 'Total amount is required for the selected advance type.',
        'no_of_emi.required_if' => 'Number of EMIs is required for the selected advance type.',
        'monthly_emi_amount.required_if' => 'Monthly EMI amount is required for the selected advance type.',
        'deduction_from_period.required_if' => 'Deduction from period is required for the selected advance type and must be a valid date.',
    ];

    private $travelModes = [
        1 => 'Bike',
        2 => 'Bus',
        3 => 'Car',
        4 => 'Flight',
        5 => 'Train'
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $advances = AdvanceApplication::with('advanceType')
            ->filter($request)
            ->createdBy() // Apply the createdBy scope
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);
        $advanceTypes = MasAdvanceTypes::where('status', 1)->get(['id', 'name']);

        foreach ($advances as $advance) {
            $advance->formatted_date = Carbon::parse($advance->transaction_date)->format('Y-m-d');
        }

        return view('advance-loan.apply.index', compact('privileges', 'advances', 'advanceTypes'));
    }

    public function create()
    {
        $advanceTypes = MasAdvanceTypes::where('status', 1)->get();
        $budgetCodes = BudgetCode::get();
        $dzongkhags = MasDzongkhag::get();
        $sifaInterestRate = InterestRate::where('advance_type_id', 7)->value('rate');
        $excludedTravelAuthorizationIds = AdvanceApplication::pluck('travel_authorization_id')->filter()->toArray(); //filter is used incase travel_authorization_id column is null to remove those
        $travelAuthorizations = TravelAuthorizationApplication::where('created_by', loggedInUser())
            ->where('status', 3)
            ->when(!empty($excludedTravelAuthorizationIds), function ($query) use ($excludedTravelAuthorizationIds) {
                $query->whereNotIn('id', $excludedTravelAuthorizationIds);
            })
            ->get(['id', 'transaction_no']); // Always fetch after conditions are applied

        $isSifaRegistered = DB::table('sifa_registrations')
            ->where('mas_employee_id', loggedInUser())
            ->value('is_registered');

        //To calculate last month net pay
        $employeeId = loggedInUser();

        // Get the first day of last month formatted as 'Y-m-d'
        $lastMonth = now()->subMonth()->startOfMonth()->format('Y-m-d');

        $netPay = DB::table('final_pay_slips')
            ->where('mas_employee_id', $employeeId)
            ->where('for_month', $lastMonth)
            ->value(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(details, '$.net_pay'))"));

        $netPay = floatval($netPay); // Cast to float safely
        $eligibilityAmount = min($netPay * 3, 100000); // Cap at 100,000 if needed

        // Fetch advance
        $advance = AdvanceApplication::where('created_by', $employeeId)->where('status', 3)->orderByDesc('id')->first();
        // dd($advance);
        // Fetch active loan EMI
        $emi = LoanEMIDeduction::where('mas_employee_id', $employeeId)->where('is_paid_off', 0)->orderByDesc('id')->first();
        //  dd($advance,$emi);
        $advance = AdvanceApplication::where('created_by', $employeeId)
            ->where('status', 4)
            ->orderByDesc('id')
            ->first();
// dd($advance);
        $remainingOutstanding = 0;

        if ($advance) {
            $totalAmount = $advance->total_amount ?? 0;
            $monthlyEmi = $advance->monthly_emi_amount ?? 0;
            $deductionFrom = $advance->deduction_from_period;

            if ($deductionFrom && $monthlyEmi > 0) {
                $deductionFromDate = Carbon::parse($deductionFrom);
                $now = Carbon::now();

                // $monthsElapsed = $deductionFromDate->diffInMonths($now) + 1; // include current month
                $monthsElapsed = $deductionFromDate->diffInMonths($now); // exclude current month
                $deductedAmount = $monthlyEmi * $monthsElapsed;

                $remainingOutstanding = max(0, $totalAmount - $deductedAmount);
            } else {
                $remainingOutstanding = $totalAmount; // no deductions yet
            }
            // dd('remainingOutstanding', $remainingOutstanding);
        }
        return view(
            'advance-loan.apply.create',
            compact(
                'advanceTypes',
                'travelAuthorizations',
                'budgetCodes',
                'dzongkhags',
                'sifaInterestRate',
                'eligibilityAmount',
                'netPay',
                'isSifaRegistered',
                'remainingOutstanding',
                'advance'
            )
        );
    }

    public function store(Request $request)
    {
        //dd($request->all());
        //define validation rules when advance to staff is applied for detail section
        $advanceApplication = new AdvanceApplication();
        $this->validate($request, $this->rules, $this->messages);
        $conditionFields = approvalHeadConditionFields(ADVANCE_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->advance_type, \App\Models\MasAdvanceTypes::class, $conditionFields ?? []);
        $attachment = "";
        //dd($approverByHierarchy, $conditionFields, $request->advance_type);

        $reqType = MasAdvanceTypes::where('id', $request->advance_type)->first();
        $lastTransaction = AdvanceApplication::latest('id')->first();
        $advanceNo = generateTransactionNumber1($reqType, $lastTransaction, 'transaction_no');

        // dd($approverByHierarchy);



        // $travelAuthorizationNo = generateTransactionNumber(\App\Models\TravelAuthorizationApplications::class, \App\Models\MasTravelType::class, $request->travel_type);

        if (AdvanceApplication::where('transaction_no', $advanceNo)->exists()) {
            // If the travel number already exists, throw an exception or return an error
            return back()->withInput()->with('msg_error', 'Advance Loan Application Number already exists. Please try again.');
        }

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        }
        try {
            DB::beginTransaction();
            $advanceApplication->transaction_no = $advanceNo;
            $advanceApplication->transaction_date = $request->date;
            $advanceApplication->advance_settlement_date = $request->advance_settlement_date ?? null;
            $advanceApplication->type_id = $request->advance_type;
            $advanceApplication->mas_employee_id = $request->employee ?? null; // only required if user applies on behalf of someone
            $advanceApplication->travel_authorization_id = $request->transaction_no ?? null;

            $advanceApplication->amount = $request->amount ?? null;
            $advanceApplication->attachment = $attachment ?? null; // Store attachment path
            $advanceApplication->total_amount = $request->total_amount ?? null;
            $advanceApplication->no_of_emi = $request->advance_type === DSA_ADVANCE ? 1 : $request->no_of_emi ?? null;
            $advanceApplication->monthly_emi_amount = $request->monthly_emi_amount ?? null;
            $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
            $advanceApplication->item_type = $request->item_type ?? null;
            $advanceApplication->remarks = $request->remarks ?? null;
            $advanceApplication->interest_rate = $request->interest_rate ?? null;
            $advanceApplication->net_payable = $request->net_payable ?? null;
            $advanceApplication->status = $approverByHierarchy['application_status'];

            $advanceApplication->save();
            if ($request->advance_type == ADVANCE_TO_STAFF && $request->details) {
                $this->saveAdvanceDetails($request->details, $advanceApplication->id);
            }

            // Create a corresponding history record for advance
            // Create a history record it detail code resides in ApplicationHistoriesService classs
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($advanceApplication->histories(), $approverByHierarchy, $request->remarks);

            DB::commit();

            if (isset($approverByHierarchy['approver_details'])) {
                $advanceType = MasAdvanceTypes::where('id', $request->advance_type)->value('name');
                $emailContent = 'has applied ' . $advanceType . ' for your endorsement.';
                $emailSubject = 'Advance';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
            }
            // dd('advanceApplication', $advanceApplication);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect()->route('apply.index')->with('msg_success', 'Advance application created successfully!');
    }



    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $empDetails = empDetails($advance->created_by);
        $advanceTypes = MasAdvanceTypes::all();
        $advance->mode_of_travel_name = $this->travelModes[$advance->mode_of_travel] ?? 'Unknown';

        $approvalDetail = getApplicationLogs(\App\Models\AdvanceApplication::class, $advance->id);

        $employeeId = loggedInUser();
        $lastMonth = now()->subMonth()->startOfMonth()->format('Y-m-d');

        $netPay = DB::table('final_pay_slips')
            ->where('mas_employee_id', $employeeId)
            ->where('for_month', $lastMonth)
            ->value(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(details, '$.net_pay'))"));

        $netPay = floatval($netPay); // cast safely
        $eligibilityAmount = min($netPay * 3, 100000);
        $advance->netPay = $netPay;

        return view('advance-loan.apply.show', compact('advance', 'advanceTypes', 'approvalDetail', 'empDetails', 'eligibilityAmount', 'netPay', 'lastMonth'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $advance = AdvanceApplication::findOrFail($id);
        $advanceTypes = MasAdvanceTypes::all(); // Fetch advance types
        $budgetCodes = BudgetCode::get();
        $dzongkhags = MasDzongkhag::get();
        $travelAuthorizations = [];
        $advanceDetails = []; // only if advance type is ADVANCE_TO_STAFF
        if ($advance->type_id == DSA_ADVANCE) {
            $travelAuthorizations = TravelAuthorizationApplication::with('details')->where('created_by', loggedInUser())
                ->where('id', $advance->travel_authorization_id)
                ->first();
        }
        if ($advance->type_id == ADVANCE_TO_STAFF) {
            $advanceDetails = AdvanceDetail::where('advance_application_id', $advance->id)->get();
        }
        $redirectUrl = null;

        return view('advance-loan.apply.edit', compact('redirectUrl', 'advance', 'advanceTypes', 'travelAuthorizations', 'budgetCodes', 'dzongkhags', 'advanceDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        // Find the advance application record by ID or fail if not found
        $advanceApplication = AdvanceApplication::findOrFail($id);

        // Initialize the $validatedData array
        $validatedData = [];

        if ($request->hasFile('attachment')) {
            // Check if there is an existing file and delete it
            if ($advanceApplication->attachment) {
                $existingFilePath = public_path($advanceApplication->attachment);
                if (file_exists($existingFilePath) && is_file($existingFilePath)) {
                    unlink($existingFilePath); // Delete the existing file
                }
            }

            // Upload the new file and save the path
            $file = $request->file('attachment');
            $path = uploadImageToDirectory($file, $this->attachmentPath); // Ensure this function generates a relative path
            $validatedData['attachment'] = $path; // Save the relative path
        } else {
            // If no new file is uploaded, keep the existing attachment path
            $validatedData['attachment'] = $advanceApplication->attachment; // Maintain the existing path
        }

        try {
            // Start a database transaction to ensure atomicity
            DB::beginTransaction();
            $advanceApplication->transaction_no = $request->transaction_no;
            $advanceApplication->date = $request->date;
            // $advanceApplication->date = $request->date;
            $advanceApplication->advance_settlement_date = $request->advance_settlement_date ?? null;
            $advanceApplication->type_id = $request->advance_type;
            $advanceApplication->mas_employee_id = $request->employee ?? null; // only required if user applies on behalf of someone
            $advanceApplication->travel_authorization_id = $request->transaction_no ?? null; // only required if user applies on behalf of someone

            $advanceApplication->amount = $request->amount ?? null;
            $advanceApplication->attachment = $attachment ?? null; // Store attachment path
            $advanceApplication->total_amount = $request->total_amount ?? null;
            $advanceApplication->no_of_emi = $request->no_of_emi ?? null;
            $advanceApplication->monthly_emi_amount = $request->monthly_emi_amount ?? null;
            $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
            $advanceApplication->item_type = $request->item_type ?? null;
            $advanceApplication->remark = $request->remark ?? null;
            $advanceApplication->interest_rate = $request->interest_rate ?? null;
            $advanceApplication->status = $advanceApplication->status;

            $advanceApplication->save();
            if ($request->advance_type == ADVANCE_TO_STAFF && $request->details) {
                $this->saveAdvanceDetails($request->details, $advanceApplication->id);
            }

            // Optionally create a history record for the advance application
            // $advanceApplication->histories()->create([
            //     'level' => 'Test Level', // This could be dynamic, depending on the use case
            //     'status' => $advanceApplication->status,
            //     'remarks' => $request->remark ?? $advanceApplication->remark,
            //     'created_by' => loggedInUser(),  // Assuming loggedInUser() fetches the current user's ID
            //     'updated_by' => loggedInUser(),
            // ]);

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Handle the error by returning back with error message
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        if ($request->redirectUrl != null) {
            return redirect()->route('advance-loan-approval.index')->with('msg_success', 'Advance application updated successfully!');
        }
        // Return a success response after the update is complete
        return redirect()->route('apply.index')->with('msg_success', 'Advance application updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            AdvanceApplication::findOrFail($id)->delete();

            return back()->with('msg_success', 'Advance Applicaton has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Advance Applicaton cannot be deleted as it is used by other modules.');
        }
    }

    public function saveAdvanceDetails($advanceDetails, $advanceApplicationId)
    {
        // Track existing IDs to avoid deleting records that are updated
        $existingIds = [];

        foreach ($advanceDetails as $detail) {
            // Check if the detail has an 'id' (indicating an existing record)
            if (isset($detail['id']) && !empty($detail['id'])) {
                // Update the existing record
                $existingDetail = AdvanceDetail::find($detail['id']);
                if ($existingDetail) {
                    $existingDetail->update([
                        'budget_code_id' => $detail['budget_code'],
                        'from_date' => isset($detail['from_date']) ? $detail['from_date'] : null,
                        'to_date' => isset($detail['to_date']) ? $detail['to_date'] : null,
                        'dzongkhag_id' => $detail['dzongkhag'],
                        'site_location' => $detail['site_location'],
                        'amount_required' => $detail['amount_required'],
                        'purpose' => $detail['purpose'],
                    ]);

                    $existingIds[] = $existingDetail->id; // Track updated record IDs
                }
            } else {
                // Insert new record
                $newDetail = AdvanceDetail::create([
                    'advance_application_id' => $advanceApplicationId,
                    'budget_code_id' => $detail['budget_code'],
                    'from_date' => isset($detail['from_date']) ? $detail['from_date'] : null,
                    'to_date' => isset($detail['to_date']) ? $detail['to_date'] : null,
                    'dzongkhag_id' => $detail['dzongkhag'],
                    'site_location' => $detail['site_location'],
                    'amount_required' => $detail['amount_required'],
                    'purpose' => $detail['purpose'],
                ]);

                if ($newDetail) {
                    $existingIds[] = $newDetail->id; // Track newly inserted record IDs
                }
            }
        }

        // Optionally delete records not in the current request
        AdvanceDetail::where('advance_application_id', $advanceApplicationId)
            ->whereNotIn('id', $existingIds)
            ->delete();
    }
}
