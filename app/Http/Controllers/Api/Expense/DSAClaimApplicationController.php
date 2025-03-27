<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\AjaxRequestController;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\ApplicationHistory;
use App\Models\DailyAllowance;
use App\Models\DsaClaimApplication;
use App\Models\DsaClaimDetail;
use App\Models\DsaClaimMappings;
use App\Models\DsaClaimType;
use App\Models\MasExpenseType;
use App\Models\TravelAuthorizationApplication;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use App\Traits\JsonResponseTrait;
use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DSAClaimApplicationController extends Controller
{   use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(AjaxRequestController $ajax)
    {
        $this->ajax = $ajax;
        $this->middleware('auth:api');
    }

    protected $rules = [

        'type_id' => 'required|exists:dsa_claim_types,id',
        'transaction_no' => 'nullable|exists:advance_applications,id',
        'amount' => 'required|numeric|min:0',
        'net_payable_amount' => 'nullable|numeric|min:0',
        'balance_amount' => 'nullable|numeric|min:0',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'remarks' => 'nullable|string|max:500',
        'dsa_claim_detail' => 'required|array|min:1',
        'dsa_claim_detail.*.from_date' => 'required|date',
        'dsa_claim_detail.*.to_date' => 'required|date|after_or_equal:dsa_claim_detail.*.from_date',
        'dsa_claim_detail.*.from_location' => 'required|string|max:255',
        'dsa_claim_detail.*.to_location' => 'required|string|max:255',
        'dsa_claim_detail.*.total_days' => 'required|numeric|min:0',
        'dsa_claim_detail.*.daily_allowance' => 'nullable|numeric|min:0',
        'dsa_claim_detail.*.travel_allowance' => 'nullable|numeric|min:0',
        'dsa_claim_detail.*.total_amount' => 'nullable|numeric|min:0',
        'dsa_claim_detail.*.remark' => 'nullable|string|max:500',
    ];


    protected $messages = [

        'type_id.required' => 'The claim type is required.',
        'type_id.exists' => 'The selected claim type is invalid.',
        'travel_authorization_id.required' => 'Travel authorization is required.',
        'amount.required' => 'The amount is required.',
        'amount.numeric' => 'The amount must be a number.',
        'amount.min' => 'The amount must be at least 0.',
        'total_number_of_days.numeric' => 'The total number of days must be a number.',
        'total_number_of_days.min' => 'The total number of days must be at least 0.',
        'net_payable_amount.numeric' => 'The net payable amount must be a number.',
        'net_payable_amount.min' => 'The net payable amount must be at least 0.',
        'balance_amount.numeric' => 'The balance amount must be a number.',
        'balance_amount.min' => 'The balance amount must be at least 0.',
        'files.file' => 'The attachment must be a valid file.',
        'files.mimes' => 'The attachment must be a file of type: jpg, jpeg, png, pdf.',
        'files.max' => 'The attachment must not exceed 2MB.',
        'dsa_claim_detail.required' => 'At least one claim detail is required.',
        'dsa_claim_detail.array' => 'The claim detail must be an array.',
        'dsa_claim_detail.min' => 'You must provide at least one claim detail.',
        'dsa_claim_detail.*.from_date.required' => 'The start date is required.',
        'dsa_claim_detail.*.from_date.date' => 'The start date must be a valid date.',
        'dsa_claim_detail.*.to_date.required' => 'The end date is required.',
        'dsa_claim_detail.*.to_date.date' => 'The end date must be a valid date.',
        'dsa_claim_detail.*.to_date.after_or_equal' => 'The end date must be after or equal to the start date.',
        'dsa_claim_detail.*.from_location.required' => 'The from location is required.',
        'dsa_claim_detail.*.from_location.string' => 'The from location must be a string.',
        'dsa_claim_detail.*.from_location.max' => 'The from location may not be greater than 255 characters.',
        'dsa_claim_detail.*.to_location.required' => 'The to location is required.',
        'dsa_claim_detail.*.to_location.string' => 'The to location must be a string.',
        'dsa_claim_detail.*.to_location.max' => 'The to location may not be greater than 255 characters.',
        'dsa_claim_detail.*.total_days.numeric' => 'The number of days must be an number.',
        'dsa_claim_detail.*.total_days.required' => 'The number of days is required.',
        'dsa_claim_detail.*.total_days.min' => 'The number of days must be at least 0.',
        'dsa_claim_detail.*.daily_allowance.numeric' => 'The daily allowance must be a number.',
        'dsa_claim_detail.*.daily_allowance.min' => 'The daily allowance must be at least 0.',
        'dsa_claim_detail.*.travel_allowance.numeric' => 'The travel allowance must be a number.',
        'dsa_claim_detail.*.travel_allowance.min' => 'The travel allowance must be at least 0.',
        'dsa_claim_detail.*.total_amount.numeric' => 'The total amount must be a number.',
        'dsa_claim_detail.*.total_amount.min' => 'The total amount must be at least 0.',
        'dsa_claim_detail.*.remark.string' => 'The remark must be a string.',
        'dsa_claim_detail.*.remark.max' => 'The remark may not be greater than 500 characters.',
    ];

    private $attachmentPath = 'images/dsa/';

    public function index(Request $request)
    {
        try{
            $user = loggedInUser();
            $dsaClaimApplications = DSAClaimApplication::where('created_by', $user)->with('expense_approved_by:id,name', 'histories:id,application_id,action_performed_by,application_type,status',  'histories.actionPerformer:id,name,username')->orderBy('created_at', 'desc')->get();
            $mappedModel = DSAClaimApplication::class;
            $dsaClaimApplications = $dsaClaimApplications->map(function ($dsaClaimApplication) use ($mappedModel) {
                $dsaClaimApplication->mapping = DsaClaimMappings::with('travelAuthorization:id,transaction_no')->where('dsa_claim_id', $dsaClaimApplication->id)->get();
                $dsaClaimApplication->rejectRemarks = getApplicationLogs($mappedModel, $dsaClaimApplication->id)->pluck('remarks')->first();
                return $dsaClaimApplication;
            });
            return $this->successResponse($dsaClaimApplications, 'DSA claim applications retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
        $privileges = $request->instance();

        return view('expense.dsa-claim.index', compact('privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   try{
       $job = Auth::user()->empJob;
        if (!$job) {
            return response()->json('msg_error', 'You do not have a job assigned to you');
        }

        $gradeId = $job->grade->id;


        $applicationIds = DsaClaimApplication::whereCreatedBy(loggedInUser())
            ->whereIn('status', [1,3])
            ->pluck('id'); // Get only the IDs


        $excludedTravelIds = collect(
            DsaClaimApplication::whereIn('id', $applicationIds)
                ->select('travel_authorization_id')
                ->union(
                    DsaClaimMappings::whereIn('dsa_claim_id', $applicationIds) // Use these IDs in mappings
                        ->select('travel_authorization_id')
                )
                ->get()
                ->pluck('travel_authorization_id')
        )->filter()->values()->toArray();

        $travels = TravelAuthorizationApplication::with([
            'advance' => function ($query) {
                $query->where('status', 3);
            }, 'details'
        ])->whereCreatedBy(loggedInUser())->whereNotIn('id', $excludedTravelIds)->whereStatus(3)->get();
        //get dsa advance which has been approved for settlement

            $dailyAllowance = DailyAllowance::whereMasGradeId($gradeId)->pluck('da_in_country')->first();

             //common function to generate combination of loggedInUser employeeId and username
             $empIdName = LoggedInUserEmpIdName();
        return response()->json(["travels"=> $travels,  "dailyAllowance" => $dailyAllowance], 200);
        //return $this->successResponse( $travels, 'DSA claim applications retrieved successfully');

    }catch(\Exception $e){
        return $this->errorResponse($e->getMessage(), 500);
    }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $validator = \Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $conditionFields = approvalHeadConditionFields(DSA_CLAIM_SETTLEMENT_APPVL_HEAD, $request);
            $approvalService = new ApprovalService();
            $approverByHierarchy = $approvalService->getApproverByHierarchy($request->type_id, \App\Models\DsaClaimType::class, $conditionFields ?? []);

            $dsaType = DsaClaimType::where('id', $request->advance_type)->first();
            $lastTransaction = DsaClaimApplication::latest('id')->first();
            $dsaClaimNo = generateTransactionNumber1($dsaType, $lastTransaction, 'transaction_no');
              
            if (DsaClaimApplication::where('transaction_no', $dsaClaimNo)->exists()) {
                return $this->errorResponse('DSA Claim Application Number already exists. Please try again.', 500);
            }

            if ($approverByHierarchy) {
                DB::beginTransaction();

                $attachments = [];

                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $travelAuthId => $file) {
                        if ($file->isValid()) {
                            try {
                                $attachmentPath = uploadImageToDirectory($file, $this->attachmentPath);
                                $attachments[$travelAuthId] = $attachmentPath;
                            } catch (\Exception $e) {
                                throw $e;
                            }
                        }
                    }
                }

                $travel_id_json = json_encode($request->travel_authorization_id ?? []);
                $advanceIdsJson = json_encode($request->advance_ids ?? []);

                $dsaClaimApplication = DsaClaimApplication::create([
                    'transaction_no' => $dsaClaimNo,
                    'type_id' => $request->type_id,
                    'travel_authorization_id' => $travel_id_json,
                    'advance_application_id' => $advanceIdsJson,
                    'advance_amount' => is_array($request->advance_amount) ? array_sum($request->advance_amount) : $request->advance_amount,
                    'amount' => $request->amount,
                    'net_payable_amount' => !is_null($request->advance_ids) ? $request->net_payable_amount : $request->amount,
                    'balance_amount' => $request->balance_amount,
                    'total_number_of_days' => $request->total_number_of_days,
                    'status' => 1,
                ]);

                if (!empty($request->travel_authorization_id)) {
                    foreach ($request->travel_authorization_id as $travel_auth) {
                        $travel_auth_data = is_array($travel_auth) ? $travel_auth : json_decode($travel_auth, true);

                        if (!isset($travel_auth_data['id'])) {
                            continue;
                        }

                        $travel_auth_id = $travel_auth_data['id'];
                        $taAmount = $request->ta_amount[$travel_auth_id] ?? 0;
                        $advanceAmount = $request->advance_amount[$travel_auth_id] ?? 0;
                        $total_days = $request->total_days[$travel_auth_id] ?? 0;

                        $attachment = isset($attachments[$travel_auth_id]) ? json_encode($attachments[$travel_auth_id]) : json_encode([]);

                        DsaClaimMappings::create([
                            'travel_authorization_id' => $travel_auth_id,
                            'dsa_claim_id' => $dsaClaimApplication->id,
                            'advance_application_id' => $travel_auth_data['advance_id'] ?? null,
                            'ta_amount' => $taAmount,
                            'advance_amount' => $advanceAmount,
                            'attachment' => $attachment,
                            'number_of_days' => $total_days
                        ]);
                    }
                }

                if (!empty($request->dsa_claim_detail)) {
                    foreach ($request->dsa_claim_detail as $detail) {
                        $dsaMapping = DsaClaimMappings::where('travel_authorization_id', $detail['travel_authorization_id'])
                            ->where('dsa_claim_id', $dsaClaimApplication->id)
                            ->first();

                        if ($dsaMapping) {
                            DsaClaimDetail::create([
                                'dsa_claim_id' => $dsaClaimApplication->id, // Fixed issue here
                                'dsa_map_id' => $dsaMapping->id,
                                'from_date' => $detail['from_date'],
                                'from_location' => $detail['from_location'],
                                'to_date' => formatDate(request('detail.to_date')),
                                'to_location' => $detail['to_location'],
                                'total_days' => $detail['total_days'],
                                'daily_allowance' => $detail['daily_allowance'],
                                'travel_allowance' => $detail['travel_allowance'] ?? 0,
                                'total_amount' => $detail['total_amount'],
                                'remark' => $detail['remark'] ?? null,
                            ]);
                        } else {
                            \Log::warning("DsaClaimMappings not found for travel_authorization_id: " . $detail['travel_authorization_id']);
                        }
                    }
                }

                $historyService = new ApplicationHistoriesService();
                $historyService->saveHistory($dsaClaimApplication->histories(), $approverByHierarchy, $request->remarks);

                DB::commit();

                if (isset($approverByHierarchy['approver_details'])) {
                    $emailContent = 'has submitted an expense request of amount Nu. ' . $dsaClaimApplication->amount . ' awaiting your approval.';
                    $emailSubject = 'DSA Claim/Settlement';
                    try {
                        Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])
                            ->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
                    } catch (\Exception $e) {
                        \Log::error('Error sending mail for DSA Claim/Settlement: ' . $e->getMessage());
                    }
                }

                return $this->successResponse([$dsaClaimApplication], 'DSA claim application created successfully');
            } else {
                return $this->errorResponse('No approval rule defined for this expense!', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
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
        try{
        $dsa = DsaClaimApplication::with('dsaClaimDetails')->findOrfail($id);
        $approvalDetail = getApplicationLogs(DsaClaimApplication::class, $id);
        return $this->successResponse([$dsa, $approvalDetail], 'DSA claim application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empIdName = LoggedInUserEmpIdName();
        $job = Auth::user()->empJob;
        if (!$job) {
            return redirect()->back()->with('msg_error', 'You do not have a job assigned to you');
        }

        $gradeId = $job->grade->id;
        $dsaClaimApplication = DsaClaimApplication::whereId($id)->first();
        $travels = TravelAuthorizationApplication::whereCreatedBy(loggedInUser())->whereStatus(3)->get();
        $dailyAllowance = DailyAllowance::whereMasGradeId($gradeId)->first();

        $excludedAdvanceIds = DsaClaimApplication::pluck('advance_application_id');
        //get dsa advance which has been approved for settlement
        $advances = AdvanceApplication::where('type_id', DSA_ADVANCE)
            ->where('created_by', loggedInUser())
            ->whereNotIn('id', $excludedAdvanceIds)
            ->get(['id', 'transaction_no'])
            ->toArray();

        return view('expense.dsa-claim.edit', compact('dsaClaimApplication', 'empIdName', 'travels', 'dailyAllowance', 'gradeId', 'advances'));
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
        //
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
            DSAClaimApplication::findOrFail($id)->delete();

            return $this->successResponse($id, 'DSA Claim Application has been deleted');
        } catch (\Exception $e) {
            return $this->errorResponse('DSA Claim Application cannot be deleted as it is used by other modules.');
        }
    }

    public function getDsaClaimNumber()
    {
        $claimCode = MasExpenseType::where('id', 3)->pluck('code')[0];

        $latestTransaction = DsaClaimApplication::latest('id')->first();

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int) substr($latestTransaction->claim_no, -4) + 1 : 1;

        // Generate the new advance number with the incremented sequence
        $claimNo = generateTransactionNumber($claimCode, $nextSequence);

        return $this->successResponse($claimNo, 'DSA Claim Number');
    }
}
