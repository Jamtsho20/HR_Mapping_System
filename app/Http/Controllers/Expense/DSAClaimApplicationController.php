<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\AjaxRequestController;
use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\AdvanceApplication;
use App\Models\ApplicationHistory;
use App\Models\DailyAllowance;
use App\Models\DsaClaimApplication;
use App\Models\DsaClaimDetail;
use App\Models\DsaClaimType;
use App\Models\TravelAuthorizationApplication;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use App\Models\DsaClaimMappings;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DSAClaimApplicationController extends Controller
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

    protected $rules = [

        'amount' => 'required',
    ];

    protected $messages = [];

    private $attachmentPath = 'images/dsa/';

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
        $excludedAdvanceIds = DsaClaimApplication::pluck('advance_application_id')->whereNotIn('status', [-1,3]);
        $excludedTravelIds = DsaClaimApplication::pluck('travel_authorization_id')->whereNotIn('status', [-1,3]);

        $travels = TravelAuthorizationApplication::whereCreatedBy(loggedInUser())->whereNotIn('id', $excludedTravelIds)->whereStatus(3)->get();

        //get dsa advance which has been approved for settlement
        $advances = AdvanceApplication::where('type_id', DSA_ADVANCE)
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

        $conditionFields = approvalHeadConditionFields(DSA_CLAIM_SETTLEMENT_APPVL_HEAD, $request); // fetching condition field for particular approval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->dsa_claim_type_id, \App\Models\DsaClaimType::class, $conditionFields ?? []);


        $dsaClaimNo = $this->ajax->getDsaClaimNumber($request->dsa_claim_type_id);

        // $travelAuthorizationNo = generateTransactionNumber(\App\Models\TravelAuthorizationApplications::class, \App\Models\MasTravelType::class, $request->travel_type);

        if (DsaClaimApplication::where('dsa_claim_no', $dsaClaimNo)->exists()) {
            // If the travel number already exists, throw an exception or return an error
            return back()->withInput()->with('msg_error', 'DSA Claim Application Number already exists. Please try again.');
        }

       //dd($request->all());

        if ($approverByHierarchy) {
            try {
                DB::beginTransaction();

                $attachments = [];

                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $travelAuthId => $file) {
                        if ($file->isValid()) {
                            try {
                                // Store the uploaded file and get its path
                                $attachments[$travelAuthId] = uploadImageToDirectory($file, $this->attachmentPath);
                            } catch (\Exception $e) {
                                \Log::error("File upload failed for Travel Auth ID: {$travelAuthId}, Error: " . $e->getMessage());
                                return back()->withInput()->with('msg_error', 'File upload failed. Please try again.');
                            }
                        }
                    }
                }


                $dsaClaimApplication = DsaClaimApplication::create([
                    'dsa_claim_no' => $dsaClaimNo,
                    'type_id' => $request->dsa_claim_type_id,
                    'travel_authorization_id' => $travel_id_json ?? null,
                    'advance_application_id' => $advanceIdsJson ?? null,
                    'advance_amount' => is_array($request->advance_amount) ? array_sum($request->advance_amount) : $request->advance_amount,
                    'amount' => $request->amount,
                    'net_payable_amount' => !is_null($request->advance_ids) ? $request->net_payable_amount : $request->amount,
                    'balance_amount' => $request->balance_amount,
                    'total_number_of_days' => $request->total_number_of_days,
                    'status' => 1,
                ]);

                $travel_auth_ids = $request->travel_authorization_id;
                $decoded_travel_auth_ids = array_map(fn($item) => json_decode($item, true), $travel_auth_ids);
                //dd($decoded_travel_auth_ids);
                // Loop through the decoded array
                foreach ($decoded_travel_auth_ids as $travel_auth) {

                    $taAmounts = $request->ta_amount;
                    $taAmount = $taAmounts[$travel_auth['id']] ?? 0;
                    $advanceAmounts = $request->advance_amount;
                    $advanceAmount = $advanceAmounts[$travel_auth['id']] ?? 0;
                    $days =  $request->total_days;
                    $total_days = $days[$travel_auth['id']] ?? 0;

                    // $attachment = isset($attachments[$travel_auth['id']]) ? json_encode($attachments[$travel_auth['id']]) : json_encode([]);
                    $attachment = isset($attachments[$travel_auth['id']]) ? $attachments[$travel_auth['id']] : null;
                    // dd($attachment);
                        $dsaMapping = DsaClaimMappings::create([
                            'travel_authorization_id' => $travel_auth['id'],
                            'dsa_claim_id' => $dsaClaimApplication->id,
                            'advance_application_id' => $travel_auth['advance_id'] ?? null,
                            'ta_amount' => $taAmount,
                            'advance_amount' => $advanceAmount,
                            'attachment' => $attachment ,
                            'number_of_days' => $total_days
                    ]);
                }
                if (isset($request->dsa_claim_detail)) {
                    foreach ($request->dsa_claim_detail as $detail) {
                        // Find the corresponding DsaClaimMappings entry based on travel_authorization_id
                        $dsaMapping = DsaClaimMappings::where('travel_authorization_id', $detail['travel_authorization_id'])
                                                      ->where('dsa_claim_id', $dsaClaimApplication->id)
                                                      ->first();

                        if ($dsaMapping) {
                            $dsaClaimDetail = DsaClaimDetail::create([
                                'dsa_claim_id' => null, // Foreign key
                                'dsa_map_id' => $dsaMapping->id,
                                'from_date' => $detail['from_date'],
                                'from_location' => $detail['from_location'],
                                'to_date' => $detail['to_date'],
                                'to_location' => $detail['to_location'],
                                'total_days' => $detail['total_days'],
                                'daily_allowance' => $detail['daily_allowance'],
                                'travel_allowance' => $detail['travel_allowance']??0,
                                'total_amount' => $detail['total_amount'],
                                'remark' => $detail['remark'] ?? null, // Optional field
                            ]);
                        } else {
                            // Log error or handle cases where the mapping does not exist
                            \Log::warning("DsaClaimMappings not found for travel_authorization_id: " . $detail['travel_authorization_id']);
                        }
                        //dd($dsaMapping,$dsaClaimDetail);
                    }
                }



                // Create a history record
                $historyService = new ApplicationHistoriesService();
                $historyService->saveHistory($dsaClaimApplication->histories(), $approverByHierarchy, $request->remarks);

                DB::commit();
                if(isset($approverByHierarchy['approver_details'])){
                    // $claimType = DsaClaimType::where('id', $request->dsa_claim_type_id)->value('name');
                    $emailContent = 'has submitted a expense request of amount Nu. ' . $dsaClaimApplication->amount . ' is awaiting your approval.';
                    $emailSubject = 'DSA Claim/Settlement';
                    try{
                        Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
                    }catch(\Exception $e){
                        \Log::error('Error sending mail for DSA Claim/Settlement' . $e->getMessage());
                    }
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
        $oldDataFlag = true;
        $travelNosString="";
        $advanceNosString="";
        $approvalDetail = getApplicationLogs(DsaClaimApplication::class, $id);
        if(DsaClaimApplication::findOrFail($id)->travel_authorization_id != null) {
            $dsa = DsaClaimApplication::findOrFail($id);
        }else{
            $dsa = DsaClaimApplication::with(['dsaClaimMappings.dsaDetails'])->findOrFail($id);

            // Extract Travel Authorization IDs
            $travelNumbers = $dsa->dsaClaimMappings->pluck('travel_authorization_id')->filter()->toArray();

            // Extract Advance Application IDs (if they exist)
            $advanceNumbers = $dsa->dsaClaimMappings->pluck('advance_application_id')->filter()->toArray();

            // Fetch Travel Authorization Numbers as key-value pairs (id => travel_no)
            $travelNos = TravelAuthorizationApplication::whereIn('id', $travelNumbers)
                ->pluck('travel_authorization_no', 'id');

            // Fetch Advance Application Numbers as key-value pairs (id => advance_no)
            $advanceNos = AdvanceApplication::whereIn('id', $advanceNumbers)
                ->pluck('advance_no', 'id');


            // Attach both travel_authorization_no and advance_no to each dsaClaimMapping
            $dsa->dsaClaimMappings->transform(function ($mapping) use ($travelNos, $advanceNos) {
                $mapping->travel_authorization_no = $travelNos[$mapping->travel_authorization_id] ?? null;
                $mapping->advance_no = $advanceNos[$mapping->advance_application_id] ?? null;

                $newDays = $mapping->number_of_days ?? 0; // Ensure total_days is available for each mapping
                 // Replace with actual daily allowance from config or DB
                 $DAILY_ALLOWANCE = $mapping->dsaDetails->first()->daily_allowance;
                if ($newDays <= 15) {
                    $mapping->formula = "$DAILY_ALLOWANCE * $newDays day(s)";
                } else {
                    $mapping->formula = "($DAILY_ALLOWANCE * 15 day(s)) + (" . ($DAILY_ALLOWANCE / 2) . " * " . ($newDays - 15) . " day(s)) =";
                }
                return $mapping;
            });

            // Now, $dsa->dsaClaimMappings contains 'travel_authorization_no' and 'advance_no' for each mapping

            $travelNosString = $travelNos->implode(', ');
            $advanceNosString = $advanceNos->implode(', ');

            $oldDataFlag = false;
            $approvalDetail = getApplicationLogs(DsaClaimApplication::class, $id);
        }


        $empDetails = empDetails($dsa->created_by);
        return view('expense.apply.dsa-show', compact('dsa', 'empDetails', 'oldDataFlag', 'travelNosString', 'advanceNosString','approvalDetail'));
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
        try {
            $this->validate($request, $this->rules, $this->messages);

            $dsaClaimApplication = DsaClaimApplication::whereId($id)->first();

            $dsaClaimApplication->travel_authorization_id = $request->travel_authorization_id;
            $dsaClaimApplication->advance_application_id = $request->advance_no ?? null;
            $dsaClaimApplication->amount = $request->amount;
            $dsaClaimApplication->net_payable_amount = !is_null($request->advance_no) ? $request->net_payable_amount : $request->amount;
            $dsaClaimApplication->balance_amount = $request->balance_amount;
            $dsaClaimApplication->save();

            if ($dsaClaimApplication) {

                $requestIds = collect($request->input('dsa_claim_detail'))
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                DsaClaimDetail::where('dsa_claim_id', $dsaClaimApplication->id)
                    ->whereNotIn('id', $requestIds)
                    ->delete();

                foreach ($request->dsa_claim_detail as $detail) {
                    // Calculate total days if not provided
                    $from = new DateTime($detail['from_date']);
                    $to = new DateTime($detail['to_date']);
                    $interval = $from->diff($to);
                    $totalDays = $interval->days;

                    // Create or update the record
                    DsaClaimDetail::updateOrCreate(
                        [
                            'dsa_claim_id' => $dsaClaimApplication->id,
                            'from_date' => $detail['from_date'],
                        ],
                        [
                            'to_date' => $detail['to_date'],
                            'from_location' => $detail['from_location'],
                            'to_location' => $detail['to_location'],
                            'total_days' => $detail['total_days'] ?? $totalDays,
                            'daily_allowance' => $detail['daily_allowance'] ?? 0,
                            'travel_allowance' => $detail['travel_allowance'] ?? 0,
                            'total_amount' => $detail['total_amount'] ?? 0,
                            'remark' => $detail['remark'],
                        ]
                    );
                }

                if ($request->hasFile('attachment')) {
                    // Fetch the old attachment from the database
                    $oldAttachments = json_decode($dsaClaimApplication->attachment ?? '[]', true);

                    // Delete old attachments from the directory
                    foreach ($oldAttachments as $oldAttachment) {
                        if (file_exists(public_path($oldAttachment))) {
                            unlink(public_path($oldAttachment));
                        }
                    }

                    // Upload the new file and get the file path
                    $attachmentPath = uploadImageToDirectory($request->file('attachment'), $this->attachmentPath);

                    // Store the new attachment as a JSON array
                    $attachment = json_encode([$attachmentPath]);

                    // Update the record in the database
                    $dsaClaimApplication->update(['attachment' => $attachment]);
                } else {
                    // Retain the old attachment if no new file is provided
                    $attachment = $dsaClaimApplication->attachment;
                }
            }
            DB::beginTransaction();
            return redirect('expense/apply-expense')->with('msg_success', 'DSA Claim/Settltment has been updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
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
        //
    }
}
