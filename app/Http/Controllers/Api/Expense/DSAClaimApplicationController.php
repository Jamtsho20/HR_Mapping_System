<?php

namespace App\Http\Controllers\Api\Expense;

use DateTime;
use Illuminate\Http\Request;
use App\Models\DailyAllowance;
use App\Models\DsaClaimDetail;
use App\Services\ApprovalService;
use App\Models\AdvanceApplication;
use Illuminate\Support\Facades\DB;
use App\Models\DsaClaimApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelAuthorizationApplication;
use App\Traits\JsonResponseTrait;
use App\Models\MasExpenseType;

use App\Services\ApplicationHistoriesService;

class DSAClaimApplicationController extends Controller
{   use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected $rules = [
        'dsa_claim_no' => 'required|string',
        'amount' => 'required',
    ];

    protected $messages = [

    ];

    public function index(Request $request)
    {
        try{
            $user = loggedInUser();
            $dsaClaimApplications = DSAClaimApplication::where('created_by', $user)->orderBy('created_at', 'desc')->get();
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
         //common function to generate combination of loggedInUser employeeId and username
         $empIdName = LoggedInUserEmpIdName();
         //dsa advance that need to be excluded (if dsa sttlement has been applied then no need to fetch those advance)
         $excludedAdvanceIds = DsaClaimApplication::pluck('advance_application_id');

        $travels = TravelAuthorizationApplication::whereCreatedBy(loggedInUser())->whereStatus(3)->get();

        //get dsa advance which has been approved for settlement
        $advances = AdvanceApplication::where('type_id', DSA_ADVANCE)
            ->where('created_by', loggedInUser())
            ->where('status', 3)
            ->whereNotIn('id', $excludedAdvanceIds)
            ->get(['id', 'advance_no']);
        return response()->json(["travels"=> $travels], 200);
        return $this->successResponse( $travels, 'DSA claim applications retrieved successfully');

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
        try{
            $validator = \Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $conditionFields = approvalHeadConditionFields(DSA_CLAIM_SETTLEMENT_APPVL_HEAD, $request); // fetching condition field for particular approval head
            $approvalService = new ApprovalService();
            $approverByHierarchy = $approvalService->getApproverByHierarchy($request->type_id, \App\Models\DsaClaimType::class, $conditionFields ?? []);

            if ($approverByHierarchy) {
                try {
                    DB::beginTransaction();

                    if ($request->hasFile('attachment')) {
                        // Upload file and get the file path
                        $attachmentPath = uploadImageToDirectory($request->file('attachment'), $this->filePath);

                        // Store it as a JSON array
                        $attachment = json_encode([$attachmentPath]);
                    } else {
                        $attachment = json_encode([]);
                    }

                    $dsaClaimApplication = DsaClaimApplication::create([
                        'dsa_claim_no' => $request->dsa_claim_no,
                        'type_id' => $request->type_id,
                        'travel_authorization_id' => $request->travel_authorization_id,
                        'advance_application_id' => $request->advance_no ?? null,
                        'amount' => $request->amount,
                        'net_payable_amount' => !is_null($request->advance_no) ? $request->net_payable_amount : $request->amount,
                        'balance_amount' => $request->balance_amount,
                        'attachment' => $attachment,
                        'status' => 1,
                    ]);

                    if ($dsaClaimApplication) {
                        foreach ($request->dsa_claim_detail as $detail) {

                            $from = new DateTime($detail['from_date']);
                            $to = new DateTime($detail['to_date']);

                            $interval = $from->diff($to);
                            $totalDays = $interval->days;

                            $applicationDetail = new DsaClaimDetail();
                            $applicationDetail->dsa_claim_id = $dsaClaimApplication->id;
                            $applicationDetail->from_date = $detail['from_date'];
                            $applicationDetail->to_date = $detail['to_date'];
                            $applicationDetail->from_location = $detail['from_location'];
                            $applicationDetail->to_location = $detail['to_location'];
                            $applicationDetail->total_days = $detail['total_days'] ?? $totalDays;
                            $applicationDetail->daily_allowance = $detail['daily_allowance'] ?? 0;
                            $applicationDetail->travel_allowance = $detail['travel_allowance'] ?? 0;
                            $applicationDetail->total_amount = $detail['total_amount'] ?? 0;
                            $applicationDetail->remark = $detail['remark'];
                            $applicationDetail->save();
                        }
                    }

                    // Create a history record
                    $historyService = new ApplicationHistoriesService();
                    $historyService->saveHistory($dsaClaimApplication->histories(), $approverByHierarchy, $request->remarks);


                    DB::commit();
                    if (isset($approverByHierarchy['approver_details'])) {
                        $emailContent = 'has submitted a expense request of amount ' . $dsaClaimApplication->total_amount . ' is awaiting your approval.';
                        $emailSubject = 'DSA Claim/Settlement Application';
                        // Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
                    }

                    } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->errorResponse($e->getMessage(), 500);
                }
            } else {
                return $this->errorResponse('No approver found', 404);
            }
            return $this->successResponse([$dsaClaimApplication, $applicationDetail], 'DSA claim application created successfully');
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
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

        return $this->successResponse($dsa, 'DSA claim application retrieved successfully');
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
            ->get(['id', 'advance_no'])
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
