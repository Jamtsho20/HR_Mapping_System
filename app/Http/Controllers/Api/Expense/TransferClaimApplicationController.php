<?php

namespace App\Http\Controllers\Api\Expense;


use App\Http\Controllers\Controller;
use App\Models\MasTransferClaim;
use App\Models\TransferClaimApplication;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;
use App\Models\MasExpenseType;
use App\Services\ApplicationHistoriesService;
use App\Http\Controllers\AjaxRequestController;

class TransferClaimApplicationController extends Controller
{
    use JsonResponseTrait;
    protected $ajax;
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
    private $filePath = 'images/files/';

    protected $rules = [

        'transfer_claim' => 'required',
        'current_location' => 'required',
        'new_location' => 'required',
        'distance_travelled' => 'required_if:transfer_claim,Carriage Charge',
        'amount' => 'required',
    ];

    protected $messages = [];

    public function index(Request $request)
    {
        try{

            $empIdName = LoggedInUserEmpIdName();
            $user = loggedInUser();

            $transferClaims = TransferClaimApplication::where('created_by', $user)->with('expense_approved_by:id,name')->orderBy('created_at', 'desc')->get();

            return $this->successResponse($transferClaims, 'Expense applications retrieved successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {

            $trasnferClaim = MasTransferClaim::get();
            return $this->successResponse($trasnferClaim, 'Expense applications create function retrieved successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   try {

        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $conditionFields = approvalHeadConditionFields(TRANSFER_CLAIM_APPVL_HEAD, $request); // fetching condition field for particular approval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->transfer_claim, \App\Models\MasTransferClaim::class, $conditionFields ?? []);
        $transferClaimNo = $this->ajax->getTransferClaimNumber($request->transfer_claim);

        // $travelAuthorizationNo = generateTransactionNumber(\App\Models\TravelAuthorizationApplications::class, \App\Models\MasTravelType::class, $request->travel_type);

        if (TransferClaimApplication::where('transfer_claim_no', $transferClaimNo)->exists()) {
            // If the travel number already exists, throw an exception or return an error
            return $this->errorResponse('Transfer Claim Application Number already exists. Please try again.', 500);
            }

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

                $transferClaimApplication = TransferClaimApplication::create([
                    'transfer_claim_no' => $transferClaimNo,
                    'type_id' => $request->transfer_claim,
                    'current_location' => $request->current_location,
                    'new_location' => $request->new_location,
                    'distance_travelled' => $request->distance_travelled,
                    'amount' => $request->amount,
                    'attachment' => $attachment,
                    'status' => 1,
                ]);

                // Create a history record
                $historyService = new ApplicationHistoriesService();
                $historyService->saveHistory($transferClaimApplication->histories(), $approverByHierarchy, $request->remarks);

                // Fetch the approver dynamically using ApprovalService and sent email to notify approver accordingly
                DB::commit();
                if (isset($approverByHierarchy['approver_details'])) {
                    $emailContent = 'has submitted a expense request of amount ' . $transferClaimApplication->expense_amount . ' is awaiting your approval.';
                    $emailSubject = 'Transfer Claim Application';
                    // Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
                }
                return $this->successResponse($transferClaimApplication, 'Transfer Claim applied successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return $this->errorResponse($e->getMessage(), 500);
            }
        } else {
            return $this->errorResponse('No approver hierarchy found', 500);
        }
    }catch (\Illuminate\Validation\ValidationException $e) {
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
    {   try {

        $transfer = TransferClaimApplication::findOrfail($id);
        return $this->successResponse($transfer, 'Expense applications show function retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
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
        $trasnferClaim = MasTransferClaim::get();
        $transfer = TransferClaimApplication::findOrfail($id);

        return view('expense.transfer-claim.edit', compact('transfer', 'empIdName', 'trasnferClaim'));
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
        try {

            $validator = \Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }
            $transfer = TransferClaimApplication::findOrFail($id);

            if ($request->hasFile('attachment')) {
                // Upload file and get the file path
                $attachmentPath = uploadImageToDirectory($request->file('attachment'), $this->filePath);

                // Store it as a JSON array
                $attachment = json_encode([$attachmentPath]);
            } else {
                $attachment = $transfer ? $transfer->attachment : json_encode([]); // Empty JSON array if null
            }

            $transfer->transfer_claim_id = $request->transfer_claim;
            $transfer->current_location = $request->current_location;
            $transfer->new_location = $request->new_location;
            $transfer->distance_travelled = $request->distance_travelled;
            $transfer->amount = $request->amount;
            $transfer->attachment = $attachment ?? $transfer->attachment;
            $transfer->save();
            return $this->successResponse($transfer, 'Transfer Claim Updated successfully');
        }catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to update application', 500);
        }

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
            TransferClaimApplication::findOrFail($id)->delete();

            return $this->successResponse($id, 'Transfer Claim Application has been deleted');
        } catch (\Exception $e) {
            return $this->errorResponse('Transfer Claim Application cannot be deleted as it is used by other modules.');
        }
    }

    public function getTransferClaimNumber()
    {
        $claimCode = MasExpenseType::where('id', 4)->pluck('code')[0];

        $latestTransaction = TransferClaimApplication::latest('id')->first();

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int) substr($latestTransaction->transfer_claim_no, -4) + 1 : 1;

        // Generate the new advance number with the incremented sequence
        $claimNo = generateTransactionNumber($claimCode, $nextSequence);

        return $this->successResponse($claimNo, 'Transfer Claim Number');
    }
}
