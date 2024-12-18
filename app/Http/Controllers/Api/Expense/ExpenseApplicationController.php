<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpenseApplication;
use App\Models\MasEmployeeJob;
use App\Models\MasExpensePolicy;
use App\Models\MasExpenseType;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DsaClaimApplication;
use App\Models\TransferClaimApplication;
use App\Models\TravelAuthorizationApplication;
use App\Http\Controllers\AjaxRequestController;
use App\Models\AdvanceApplication;
use App\Models\MasTransferClaim;
use App\Models\MasTravelType;
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;
use App\Services\ApprovalService;
use App\Services\ApplicationHistoriesService;
use App\Models\DailyAllowance;
use App\Models\MasVehicle;
use Illuminate\Support\Facades\Auth;


class ExpenseApplicationController extends Controller
{

    use JsonResponseTrait;
    protected $ajaxRequestController;
    protected $ajax;
    public function __construct(AjaxRequestController $ajaxRequestController)
    {
        $this->middleware('auth:api');
        $this->ajaxRequestController = $ajaxRequestController;
        $this->ajax = $ajaxRequestController;
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
        try {
            $privileges = $request->instance();
            $headers = MasExpenseType::whereIn('id', [2, 3, 4])->get();
            $user = loggedInUser();
            $empIdName = LoggedInUserEmpIdName();

            $expenseApplications = ExpenseApplication::with(['type:id,name', 'travelType:id,name'])->filter($request)->createdBy()->orderBy('created_at', 'desc')->get();

            $dsaClaimApplications = DsaClaimApplication::filter($request)->createdBy()->orderBy('created_at', 'desc')->get();
            $transferClaims = TransferClaimApplication::where('created_by', $user)->orderBy('created_at', 'desc')->get();

            return response()->json([
                'expenseApplications' => $expenseApplications,
                'dsaClaimApplications' => $dsaClaimApplications,
                'transferClaims' => $transferClaims,
            ]);
            // return $this->successResponse([$expenseApplications,  $empIdName, $dsaClaimApplications, $transferClaims], 'Expense applications retrieved successfully');
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
    }


    public function show($id)
    {

        try {
            $expense = ExpenseApplication::findOrFail($id);
            return $this->successResponse($expense, 'Expense application retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function fetchExpenseNumber($id)
    {
        $expenseNo = $this->ajaxRequestController->getExpenseNumber($id);

        return response()->json([
            'expense_no' => $expenseNo,
        ]);
    }


    public function create(Request $request)
    {
        try {
            $itemType = $request->get('item_type', null);

            // Fetch required data for creating the expense application
            // $expenses = MasExpenseType::whereNotIn('id', [3, 4])->get();
            $expenses = MasExpenseType::whereNotIn('id', [1, 2, 3, 4])->get(); // exclude general and conveyance and dsa claim and transfer claim
            $headers = MasExpenseType::whereIn('id', [2, 3, 4])->get();
            $empIdName = LoggedInUserEmpIdName(); // Get logged-in user's employee details
            $travelTypes = MasTravelType::get();

            $excludedAdvanceIds = DsaClaimApplication::pluck('advance_application_id');
            $advances = AdvanceApplication::where('type_id', DSA_ADVANCE)
                ->where('created_by', loggedInUser())
                ->whereNotIn('id', $excludedAdvanceIds)
                ->get(['id', 'advance_no']);

            $transferClaimTypes = MasTransferClaim::select('id', 'name')->get();

            $job = Auth::user()->empJob;
            $gradeId = $job->grade->id;

            $travels = TravelAuthorizationApplication::whereCreatedBy(loggedInUser())->whereStatus(3)->get();
            $dailyAllowance = DailyAllowance::whereMasGradeId($gradeId)->first();
            $vehicles = MasVehicle::all();
            $dsaClaimNo = $this->ajax->getDsaClaimNumber();
            $transferClaimNo = $this->ajax->getTransferClaimNumber();

            ///

            // Return JSON response with all the prepared data
            return response()->json([
                'success' => true,
                'message' => 'Expense application create function executed successfully!',
                'data' => [
                    'expenses' => $expenses,
                    'headers' => $headers,
                    'travelTypes' => $travelTypes,
                    'empIdName' => $empIdName,
                    'advances' => $advances,
                    'transferClaimTypes' => $transferClaimTypes,
                    'dailyAllowance' => $dailyAllowance,
                    'vehicles' => $vehicles,
                    'itemType' => $itemType,
                    'travels' => $travels,
                    'dsaClaimNo' => $dsaClaimNo,
                    'transferClaimNo' => $transferClaimNo,
                ]
            ]);
        } catch (\Exception $e) {
            // Return error response in case of failure
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function store(Request $request)
    {

        if ($request->expense_type == 5) {
            // $request->validate([
            //     'fuel_claim_details.*.date' => 'required|date',
            //     'fuel_claim_details.*.initial_reading' => 'required|numeric',
            //     'fuel_claim_details.*.final_reading' => 'required|numeric',
            //     'fuel_claim_details.*.quantity' => 'required|numeric',
            // ]);
        } else {
            $request->request->remove('fuel_claim_details');
        }

        $validator = \Validator::make($request->all(), $this->rules($request), $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $result = $this->handleExpenseApplication($request);

        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $this->errorResponse('File upload failed.', 400);
        }



        $conditionFields = approvalHeadConditionFields(EXPENSE_APPVL_HEAD, $request); // fetching condition field for particular approval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->expense_type, \App\Models\MasExpenseType::class, $conditionFields ?? []);
        if ($approverByHierarchy) {
            try {
                DB::beginTransaction();

                $expenseApplication = ExpenseApplication::create([
                    // 'mas_employee_id' => loggedInUser(),
                    'expense_no' => $request->expense_no,
                    'type_id' => $request->expense_type,
                    'mas_vehicle_id' => $request->mas_vehicle_id ?? null,
                    'date' => formatDate($request->date),
                    'amount' => $request->amount,
                    'description' => $request->description,
                    'file' => $result['file'],
                    'travel_type' => $request->travel_type,
                    'travel_mode' => $request->mode_of_travel,
                    'travel_from_date' => formatDate($request->travel_from_date),
                    'travel_to_date' => formatDate($request->travel_to_date),
                    'travel_from' => $request->travel_from,
                    'travel_to' => $request->travel_to,
                    'status' => $request->status ?? 1,
                ]);

                if ($expenseApplication) {
                    if ($request->has('fuel_claim_details')) { // for fuel claim
                        foreach ($request->input('fuel_claim_details') as $key => $detail) {
                            // Access each detail
                            $date = formatDate($detail['date']);
                            $initialReading = $detail['initial_reading'] ?? 1000;
                            $finalReading = $detail['final_reading'] ?? 1234;
                            $quantity = $detail['quantity'] ?? 24;
                            $mileage = $detail['mileage'] ?? 8;
                            $rate = $detail['rate'] ?? 8;
                            $amount = $detail['amount'] ?? 3535;
                            $expenseApplication->details()->create([
                                'date' => $date,
                                'initial_reading' => $initialReading,
                                'final_reading' => $finalReading,
                                'quantity' => $quantity,
                                'mileage' => $mileage,
                                'rate' => $rate,
                                'amount' => $amount,
                            ]);
                            // Save to database or perform logic
                            // ExpenseFuelClaimDetail::create([
                            //     'expense_id' => $expenseApplication->id,
                            //     'date' => $date,
                            //     'initial_reading' => $initialReading,
                            //     'final_reading' => $finalReading,
                            //     'quantity' => $quantity,
                            //     'mileage' => $mileage,
                            //     'rate' => $rate,
                            //     'amount' => $amount,
                            // ]);
                        }
                    }
                }
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
                return response()->json([
                    'expenseApplication' => $expenseApplication,
                    'message' => 'Expense application has been successfully created.',
                ]);


        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error creating expense application: ' . $e->getMessage());

            return $this->errorResponse('An error occurred while processing your request.', 500, [
                'details' => $e->getMessage(),
            ]);
        }
    }

    }

    public function edit($id)
    {
        try{
        $expenses = MasExpenseType::all();
        $expenseApplication = ExpenseApplication::findOrfail($id);
        $vehicles = MasVehicle::all();
        return response()->json([$expenseApplication, $expenses, $vehicles], 200);
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
        return view('expense.apply.edit', compact('expenses', 'expenseApplication', 'vehicles'));
    }
public function update(Request $request, $id)
    {
        $expenseApplication = ExpenseApplication::findOrFail($id);

        $result = $this->handleExpenseApplication($request, $expenseApplication);
        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $this->errorResponse('File upload failed.', 400);
        }

        $validator = \Validator::make($request->all(), $this->rules($request), $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $request->validate($this->rules($request));
        $date= formatDate(request('date'));
        try {
            DB::beginTransaction();
            $expenseApplication->update([
                'type_id' => $request->expense_type,
                'mas_vehicle_id' => $request->mas_vehicle_id ?? null,
                'date' =>  $date,
                'amount' => $request->amount,
                'description' => $request->description,
                'file' => $result['attachment'] ?? $expenseApplication->file,
                'travel_type' => $request->travel_type,
                'travel_mode' => $request->mode_of_travel,
                'travel_from_date' => formatDate($request->travel_from_date),
                'travel_to_date' => formatDate($request->travel_to_date),
                'travel_from' => $request->travel_from,
                'travel_to' => $request->travel_to,
                'status' => $request->status ?? 1,
            ]);

            if ($request->has('fuel_claim_details')) {

                $requestIds = collect($request->input('fuel_claim_details'))
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                ExpenseFuelClaimDetail::where('expense_id', $expenseApplication->id)
                    ->whereNotIn('id', $requestIds)
                    ->delete();

                foreach ($request->input('fuel_claim_details') as $key => $detail) {
                    $date =  formatDate($detail['date']);
                    $initialReading = $detail['initial_reading'] ?? 1000;
                    $finalReading = $detail['final_reading'] ?? 1234;
                    $quantity = $detail['quantity'] ?? 24;
                    $mileage = $detail['mileage'] ?? 8;
                    $rate = $detail['rate'] ?? 8;
                    $amount = $detail['amount'] ?? 3535;

                    $fuelClaimId = $detail['id'] ?? null;

                    ExpenseFuelClaimDetail::updateOrCreate(
                        [
                            'id' => $fuelClaimId,
                        ],
                        [
                            'expense_id' => $expenseApplication->id,
                            'date' => $date,
                            'initial_reading' => $initialReading,
                            'final_reading' => $finalReading,
                            'quantity' => $quantity,
                            'mileage' => $mileage,
                            'rate' => $rate,
                            'amount' => $amount,
                        ]
                    );
                }

            }

            return $this->successResponse($expenseApplication, 'Expense application has been successfully updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {

            $expenseApplication = ExpenseApplication::findOrFail($id);
            $expenseApplication->delete();

            return $this->successResponse($expenseApplication, 'Expense application has been deleted successfully!');


        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while processing your request.', 500);
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
            ->where('type_id', $request->expense_type)
            ->whereStatus(1)
            ->first();
        //check weather attachment is required while applying expense from expense policy
        $attachmentRequired = $expensePolicy && $expensePolicy->rateDefinition ? $expensePolicy->rateDefinition->attachment_required : 0;
        $expenseType = $expensePolicy && $expensePolicy->expenseType ? $expensePolicy->expenseType->name : '';

        //validation based on expense policy rate(at once how much amount user can apply based on region and grade steps)
        if ($expensePolicy && $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount < $request->amount) {
            $limitAmount = $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount;
            // $region = DB::table('mas_regions')->where('id', $expensePolicy->rateDefinition->expenseRateLimits[0]->mas_region_id)->first();
            return response()->json(['error' => 'You cannot apply more than Nu. ' . $limitAmount . ' for expense type ' . $expenseType . ' from ' . $loggedInUserRegion[0]->region_name . ' region.'], 400);
        }

        // Handle file upload if required based on defined in leave policy
        $attachment = $expenseApplication ? $expenseApplication->attachment : '';
        // if ($attachmentRequired && !$attachment) {
        if ($attachmentRequired && !$attachment) {
            $validator = \Validator::make($request->all(),  ['file' => 'required|file|mimes:pdf,jpg,png|max:2048'], ['file.required' => 'The file is required. Please upload a file.']);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
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
