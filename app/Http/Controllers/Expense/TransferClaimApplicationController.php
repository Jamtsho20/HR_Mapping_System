<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\MasTransferClaim;
use App\Models\TransferClaimApplication;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\services\ApplicationHistoriesService;

class TransferClaimApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:expense/transfer-claim,view')->only('index');
        $this->middleware('permission:expense/transfer-claim,create')->only('store');
        $this->middleware('permission:expense/transfer-claim,edit')->only('update');
        $this->middleware('permission:expense/transfer-claim,delete')->only('destroy');
    }
    private $filePath = 'images/files/';

    protected $rules = [
        'transfer_claim_no' => 'required|string',
        'transfer_claim' => 'required',
        'current_location' => 'required',
        'new_location' => 'required',
        'distance_travelled' => 'required_if:transfer_claim,Carriage Charge',
        'amount_claimed' => 'required',
    ];

    protected $messages = [];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $empIdName = LoggedInUserEmpIdName();
        $user = loggedInUser();

        $transferClaims = TransferClaimApplication::where('created_by', $user)->get();

        return view('expense.transfer-claim.index', compact('privileges', 'transferClaims', 'empIdName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empIdName = LoggedInUserEmpIdName();
        $trasnferClaim = MasTransferClaim::get();
        return view('expense.transfer-claim.create', compact('trasnferClaim', 'empIdName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, $this->rules, $this->messages);

        $conditionFields = approvalHeadConditionFields(EXPENSE_APPVL_HEAD, $request); // fetching condition field for particular approval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy(TRANSFER_CLAIM_EXPENSE_TYPE, \App\Models\MasExpenseType::class, $conditionFields ?? []);

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
                    'transfer_claim_no' => $request->transfer_claim_no,
                    'transfer_claim_id' => $request->transfer_claim,
                    'current_location' => $request->current_location,
                    'new_location' => $request->new_location,
                    'distance_travelled' => $request->distance_travelled,
                    'amount_claimed' => $request->amount_claimed,
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

                return redirect('expense/apply-expense')->with('msg_success', 'Transfer Claim applied successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
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
        $empIdName = LoggedInUserEmpIdName();

        $transfer = TransferClaimApplication::findOrfail($id);


        return view('expense.transfer-claim.show', compact('transfer', 'empIdName'));
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
        $this->validate($request, $this->rules, $this->messages);
        $transfer = TransferClaimApplication::findOrFail($id);

        if ($request->hasFile('attachment')) {
            // Upload file and get the file path
            $attachmentPath = uploadImageToDirectory($request->file('attachment'), $this->filePath);

            // Store it as a JSON array
            $attachment = json_encode([$attachmentPath]);
        } else {
            $attachment = $transfer ? $transfer->attachment : json_encode([]); // Empty JSON array if null
        }

        $transfer->transfer_claim = $request->transfer_claim;
        $transfer->current_location = $request->current_location;
        $transfer->new_location = $request->new_location;
        $transfer->distance_travelled = $request->distance_travelled;
        $transfer->amount_claimed = $request->amount_claimed;
        $transfer->attachment = $attachment ?? $transfer->attachment;
        $transfer->save();

        return redirect('expense/transfer-claim')->with('msg_success', 'Transfer Claim Updated successfully');
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

            return back()->with('msg_success', 'Transfer Claim Application has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Transfer Claim Application cannot be deleted as it is used by other modules.');
        }
    }
}
