<?php

namespace App\Http\Controllers\Expense;

use Illuminate\Http\Request;
use App\Services\ApprovalService;
use App\Models\AdvanceApplication;
use Illuminate\Support\Facades\DB;
use App\Models\DsaClaimApplication;
use App\Http\Controllers\Controller;
use App\Models\TravelAuthorizationApplication;

class DSAClaimApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:expense/dsa-claim-settlement,view')->only('index');
        $this->middleware('permission:expense/dsa-claim-settlement,create')->only('store');
        $this->middleware('permission:expense/dsa-claim-settlement,edit')->only('update');
        $this->middleware('permission:expense/dsa-claim-settlement,delete')->only('destroy');
    }

    protected $rules = [
        'dsa_claim_no' => 'required|string',
        'total_amount' => 'required',
    ];

    protected $messages = [

    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();

        return view('expense.dsa-claim.index', compact('privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //common function to generate combination of loggedInUser employeeId and username
        $empIdName = LoggedInUserEmpIdName();
        //dsa advance that need to be excluded (if dsa sttlement has been applied then no need to fetch those advance)
        $excludedAdvanceIds = DsaClaimApplication::pluck('advance_application_id');

        $travels = TravelAuthorizationApplication::whereCreatedBy(loggedInUser())->whereStatus(3)->get();

        //get dsa advance which has been approved for settlement
        $advances = AdvanceApplication::where('advance_type_id', DSA_ADVANCE)
            ->where('created_by', loggedInUser())
            ->where('status', 3)
            ->whereNotIn('id', $excludedAdvanceIds)
            ->get(['id', 'advance_no'])
            ->toArray();
        return view('expense.dsa-claim.create', compact('empIdName', 'advances'));

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
        $approverByHierarchy = $approvalService->getApproverByHierarchy(DSA_CLAIM_SETTLEMENT_EXPENSE_TYPE, \App\Models\MasExpenseType::class, $conditionFields ?? []);

        dd($approverByHierarchy);
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
                    'advance_application_id' => $request->advance_no,
                    'total_amount' => $request->total_amount,
                    'net_payable_amount' => $request->net_payable_amount,
                    'balance_amount' => $request->balance_amount,
                    'attachment' => $attachment,
                    'status' => 1
                ]);



                DB::commit();
                if (isset($approverByHierarchy['approver_details'])) {
                    $emailContent = 'has submitted a expense request of amount ' . $dsaClaimApplication->total_amount . ' is awaiting your approval.';
                    $emailSubject = 'DSA Claim/Settlement Application';
                    // Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
                }

                return redirect('expense/apply-expense')->with('msg_success', 'DSA Claim/Settltment has been applied successfully!');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
