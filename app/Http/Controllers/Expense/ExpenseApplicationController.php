<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\AjaxRequestController;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\DailyAllowance;
use App\Models\DsaClaimApplication;
use App\Models\ExpenseApplication;
use App\Models\MasEmployeeJob;
use App\Models\MasExpensePolicy;
use App\Models\MasExpenseType;
use App\Models\MasTransferClaim;
use App\Models\TransferClaimApplication;
use App\Models\TravelAuthorizationApplication;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ApplicationHistoriesService;
class ExpenseApplicationController extends Controller
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

        $this->middleware('permission:expense/apply-expense,view')->only('index');
        $this->middleware('permission:expense/apply-expense,create')->only('store');
        $this->middleware('permission:expense/apply-expense,edit')->only('update');
        $this->middleware('permission:expense/apply-expense,delete')->only('destroy');
    }

    protected function rules(Request $request)
    {
        $rules = [
            'expense_type' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required',
        ];

        // Add conveyance-specific rules if the expense type is CONVEYANCE_EXPENSE
        if ($request->input('expense_type') == CONVEYANCE_EXPENSE) {
            $rules['travel_type'] = 'required';
            $rules['mode_of_travel'] = 'required';
            $rules['travel_from_date'] = 'required|date';
            $rules['travel_to_date'] = 'required|date|after_or_equal:travel_from_date';
            $rules['travel_from'] = 'required';
            $rules['travel_to'] = 'required';
        }

        return $rules;
    }

    protected $messages = [
        'travel_type.required_if' => 'Travel type is required for the selected expense type.',
        'mode_of_travel.required_if' => 'Mode of travel is required for the selected expense type.',
        'travel_from_date.required_if' => 'Travel from date is required for the selected expense type.',
        'travel_to_date.required_if' => 'Travel to date is required for the selected expense type.',
        'travel_to_date.date' => 'Travel to date must be equal or greater than travel from date for selected expense type.',
        'travel_from.required_if' => 'Travel from is required for the selected expense type.',
        'travel_to.required_if' => 'Travel to is required for the selected expense type.',
    ];

    private $attachmentPath = 'images/expenses/';

    public function index(Request $request)
    {

        $privileges = $request->instance();
        $headers = MasExpenseType::whereIn('id', [2, 3, 4])->get();
        $user = loggedInUser();
        $empIdName = LoggedInUserEmpIdName();
        $expenseTypes = MasExpenseType::get(['id', 'name']);
        $expenseApplications = ExpenseApplication::filter($request)->createdBy()->paginate(config('global.pagination'));
        $dsaClaimApplications = DsaClaimApplication::filter($request)->createdBy()->paginate(config('global.pagination'));
        $transferClaims = TransferClaimApplication::where('created_by', $user)->get();

        return view('expense.apply.index', compact('privileges', 'expenseTypes', 'headers', 'empIdName', 'expenseApplications', 'dsaClaimApplications', 'transferClaims'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $itemType = $request->get('item_type', null);

        $expenses = MasExpenseType::whereNotIn('id', [3, 4])->get();
        $headers = MasExpenseType::whereIn('id', [2, 3, 4])->get();

        $job = Auth::user()->empJob;
        if (!$job) {
            return redirect()->back()->with('msg_error', 'You do not have a job assigned to you');
        }

        $gradeId = $job->grade->id;

        //common function to generate combination of loggedInUser employeeId and username
        $empIdName = LoggedInUserEmpIdName();
        //dsa advance that need to be excluded (if dsa sttlement has been applied then no need to fetch those advance)
        $excludedAdvanceIds = DsaClaimApplication::pluck('advance_application_id');
        //get dsa advance which has been approved for settlement
        $advances = AdvanceApplication::where('advance_type_id', DSA_ADVANCE)
            ->where('created_by', loggedInUser())
            ->whereNotIn('id', $excludedAdvanceIds)
            ->get(['id', 'advance_no'])
            ->toArray();

        $transferClaimTypes = MasTransferClaim::select('id', 'name')->get();

        $travels = TravelAuthorizationApplication::whereCreatedBy(loggedInUser())->whereStatus(3)->get();
        $dailyAllowance = DailyAllowance::whereMasGradeId($gradeId)->first();
        $dsaClaimNo = $this->ajax->getDsaClaimNumber();
        $transferClaimNo = $this->ajax->getTransferClaimNumber();

        return view('expense.apply.create', compact('expenses', 'headers', 'empIdName', 'advances', 'transferClaimTypes', 'itemType', 'travels', 'dailyAllowance', 'dsaClaimNo', 'transferClaimNo'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->handleExpenseApplication($request);

        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }

        $request->validate($this->rules($request));

        $conditionFields = approvalHeadConditionFields(EXPENSE_APPVL_HEAD, $request); // fetching condition field for particular approval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->expense_type, \App\Models\MasExpenseType::class, $conditionFields ?? []);
        if ($approverByHierarchy) {
            try {
                DB::beginTransaction();

                $expenseApplication = ExpenseApplication::create([
                    // 'mas_employee_id' => loggedInUser(),
                    'expense_no' => $request->expense_no,
                    'mas_expense_type_id' => $request->expense_type,
                    'date' => $request->date,
                    'expense_amount' => $request->amount,
                    'description' => $request->description,
                    'file' => $result['file'],
                    'travel_type' => $request->travel_type,
                    'travel_mode' => $request->mode_of_travel,
                    'travel_from_date' => $request->travel_from_date,
                    'travel_to_date' => $request->travel_to_date,
                    'travel_from' => $request->travel_from,
                    'travel_to' => $request->travel_to,
                    'status' => $request->status ?? 1,
                ]);

                // Create a history record
                $historyService = new ApplicationHistoriesService();
                $historyService->saveHistory($expenseApplication->histories(), $approverByHierarchy, $request->remarks);
                 

                // Fetch the approver dynamically using ApprovalService and sent email to notify approver accordingly
                DB::commit();
                if (isset($approverByHierarchy['approver_details'])) {
                    $emailContent = 'has submitted a expense request of amount ' . $expenseApplication->expense_amount . ' is awaiting your approval.';
                    $emailSubject = 'Expense Application';
                    // Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
                }

                return redirect('expense/apply-expense')->with('msg_success', 'Expense has been applied successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->with('msg_error', $e->getMessage());
            }

        } else {
            return back()->withInput()->with('msg_error', 'No approval rule defined found for this expense!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = ExpenseApplication::findOrfail($id);
        return view('expense.apply.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenses = MasExpenseType::all();
        $expenseApplication = ExpenseApplication::findOrfail($id);
        return view('expense.apply.edit', compact('expenses', 'expenseApplication'));
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
        $expenseApplication = ExpenseApplication::findOrFail($id);

        $result = $this->handleExpenseApplication($request, $expenseApplication);
        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }

        $request->validate($this->rules($request));
        try {
            DB::beginTransaction();
            $expenseApplication->update([
                // 'mas_employee_id' => $expenseApplication->mas_employee_id,
                'mas_expense_type_id' => $request->expense_type,
                'date' => $request->date,
                'expense_amount' => $request->amount,
                'description' => $request->description,
                'file' => $result['attachment'] ?? $expenseApplication->file,
                'travel_type' => $request->travel_type,
                'travel_mode' => $request->mode_of_travel,
                'travel_from_date' => $request->travel_from_date,
                'travel_to_date' => $request->travel_to_date,
                'travel_from' => $request->travel_from,
                'travel_to' => $request->travel_to,
                'status' => $request->status ?? 1,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('expense/apply-expense')->with('msg_success', 'Expense application has been updated successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        {
            try {
                ExpenseApplication::findOrFail($id)->delete();

                return back()->with('msg_success', 'Expense Applicaton has been deleted');
            } catch (\Exception $e) {
                return back()->with('msg_error', 'Expense Applicaton cannot be deleted as it is used by other modules.');
            }
        }
    }

    private function handleExpenseApplication(Request $request, $expenseApplication = null)
    { //common function to handle store and update of expense
        /// query to fetch employee grade step and region
        $empJobDetail = MasEmployeeJob::where('mas_employee_id', loggedInUser())->first();
        // dd($empJobDetail);
        $loggedInUserRegion = loggedInUserRegion(); //defined in helpers.php to get loggedInUser region id and name for common use
        //query to expense policy details
        $expensePolicy = MasExpensePolicy::with(['rateDefinition' => function ($query) use ($request, $empJobDetail, $loggedInUserRegion) {
            // Filter rateDefinition by travel type
            $query->where('travel_type', $request->travel_type ?? DOMESTIC_TRAVEL_TYPE)
                ->with(['expenseRateLimits' => function ($q) use ($empJobDetail, $loggedInUserRegion) {
                    // Filter expenseRateLimits by grade step and region
                    $q->where('mas_grade_step_id', $empJobDetail->mas_grade_step_id)
                        ->where('mas_region_id', $loggedInUserRegion[0]->region_id)
                        ->whereStatus(1);
                }]);
        }, 'policyEnforcement'])
            ->where('mas_expense_type_id', $request->expense_type)
            ->whereStatus(1)
            ->first();
        //check weather attachment is required while applying expense from expense policy
        $attachmentRequired = $expensePolicy && $expensePolicy->rateDefinition ? $expensePolicy->rateDefinition->attachment_required : 0;
        $expenseType = $expensePolicy && $expensePolicy->expenseType ? $expensePolicy->expenseType->name : '';

        //validation based on expense policy rate(at once how much amount user can apply based on region and grade steps)
        if ($expensePolicy && $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount < $request->amount) {
            $limitAmount = $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount;
            // $region = DB::table('mas_regions')->where('id', $expensePolicy->rateDefinition->expenseRateLimits[0]->mas_region_id)->first();
            return back()->withInput()->with('msg_error', 'You cannot apply more than Nu. ' . $limitAmount . ' for expense type ' . $expenseType . ' from ' . $loggedInUserRegion[0]->region_name . ' region.');
        }

        // Handle file upload if required based on defined in leave policy
        $attachment = $expenseApplication ? $expenseApplication->attachment : '';
        // if ($attachmentRequired && !$attachment) {
        if ($attachmentRequired && !$attachment) {
            $this->validate($request,
                ['file' => 'required|file|mimes:pdf,jpg,png|max:2048'],
                ['file.required' => 'The file is required. Please upload a file.']
            );
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($expenseApplication && $expenseApplication->attachment && file_exists(public_path($this->attachmentPath . $expenseApplication->attachment))) {
                delete_image($this->attachmentPath . $expenseApplication->attachment); // Delete old attachment
            }
            $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        }

        return [
            'file' => $attachment,
        ];
    }
}
