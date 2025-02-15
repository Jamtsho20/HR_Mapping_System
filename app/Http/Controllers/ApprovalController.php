<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SAP\ApiController;
use App\Models\MasApprovalHead;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\ApprovalNotificationMail;
use App\Mail\InitiatorNotificationMail;
use App\Mail\TravelApprovalMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\ApplicationHistory;
use App\Models\TravelAuthorizationApplication;
use App\Models\DsaClaimApplication;
use App\Models\AdvanceApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyAllowance;
use App\Models\DsaClaimMappings;
use App\Models\DsaClaimDetail;

class ApprovalController extends Controller
{
    protected $sap;

    public function __construct(ApiController $sap)
    {
        $this->middleware('permission:approval/applications,view')->only('index', 'approveReject', 'show');
        // $this->middleware('permission:approval/approved-applications/details,view')->only('index', 'approveReject', 'show');
        $this->sap = $sap;
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $headers = MasApprovalHead::all();
        $empIdName = LoggedInUserEmpIdName();
        $user = auth()->user();

        $applicationModels = config('global.applications');




        $results = collect();


        foreach ($applicationModels as $key => $model) {
            $modelClass = $model['name'];

            $data = $modelClass::whereHas('histories', function ($query) use ($user, $modelClass) {
                $query->where('approver_emp_id', $user->id)
                    ->where('application_type', $modelClass);
            })
                ->whereNotIn('status', [-1, 3])
                ->filter($request, false)
                ->orderBy('created_at')
                ->paginate(config('global.pagination'))
                ->withQueryString();

            $results->put($key, $data);
            foreach ($headers as $header) {
                $header->count = $results->has($header->id) ? $results->get($header->id)->total() : 0;
            }
        }
        $holidays;
        if ($results->get(7)) {
            $holidays = DB::table('work_holiday_lists')
                ->select('start_date', 'end_date')
                ->get();
        }
        return view('approval.index', compact('privileges', 'headers', 'results', 'holidays'));
    }

    /**
     * Handle the incoming request.
     */
    public function approveReject(Request $request)
    {

        $applicationModel = config('global.applications')[$request->item_type_id];
        $model = $applicationModel['name'];

        $applicationType = $request->item_type_id; // Leave / Expense / Advance / Dsa Claim / Transfer Carriage / Transfer Grant
        $action = $request->action;
        $itemIds = $request->item_ids;

        $status = ($action === 'approve') ? 2 : -1;
        $rejectRemarks = $request->input('reject_remarks', '');
        $actionBy = auth()->id();
        $responseMessage = $action === 'approve' ? 'approved.' : 'rejected.';
        DB::beginTransaction();
        try {
            $approvalService = new ApprovalService();

            foreach ($itemIds as $id) {
                $application = $model::findOrFail($id);

                if (!$application) {
                    continue;
                }

                $costingCode = null;
                $type = $application->type;

                if ($applicationType == 2) { // Expense
                    $typeId = $type->id;

                    if ($typeId == 5 || $typeId == 6) { // Vehicle Fuel Claim or Parking Fee
                        $costingCode = $application->vehicle->vehicle_no;
                    }
                }
                $applicationHistory = $application->histories->where('application_type', $model)->where('application_id', $id)->first();


                // Update application status
                $application->update([
                    'status' => $status,
                    'updated_by' => $actionBy,
                ]);

                // Forward application if approved
                $updateData = [
                    'status' => $status,
                    'remarks' => $rejectRemarks,
                    'action_performed_by' => $actionBy,
                ];

                if ($action === 'approve' && $applicationHistory) {
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, $model);

                    if ($applicationForwardedTo && isset($applicationForwardedTo['next_level'])) {
                        $updateData = array_merge($updateData, [
                            'next_level_id' => $applicationForwardedTo['next_level']->id,
                            'approver_role_id' => $applicationForwardedTo['approver_details']['approver_role_id'],
                            'approver_emp_id' => $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $applicationForwardedTo['next_level']->sequence,
                        ]);
                    } elseif ($applicationForwardedTo && isset($applicationForwardedTo['application_status']) && $applicationForwardedTo['application_status'] === 'max_level_reached') {
                        $accountCode = $type->code ?? null;
                        $memo = $type->name ?? null;
                        $shortName = $application->employee->username;
                        $contactNo = $application->employee->contact_number;
                        $amount = $application->amount;
                        $tax_amount = $application->tax_amount ?? null;
                        $postToSap = $type->post_to_sap;
                        $costingCode2 = $application->employee?->empJob?->department?->code; // department code

                        if ($postToSap) {
                            if ($applicationHistory && $applicationHistory->is_posted_to_sap === 1) {
                                Log::info('Application ID ' . $id . ' already posted to SAP. Skipping.');
                                continue;
                            }

                            // Post to SAP after final Approval
                            $officeLocation = $application->employee->empJob->office->code ?? null;
                            $postFields = $this->preparePostFields($memo, $shortName, $accountCode, $costingCode, $costingCode2, $amount, $officeLocation, $contactNo, $tax_amount);

                            Log::info($postFields);
                            $postJournalEntriesResponse = $this->sap->postJournalEntries($postFields);
                            $statusCode = $postJournalEntriesResponse->getStatusCode();
                            $postJournalEntriesResponse = json_decode($postJournalEntriesResponse->getContent(), true);

                            if ($statusCode != 201) {
                                throw new \Exception('SAP Error - ' . $postJournalEntriesResponse['msg_error'] ?? 'Unknown error during SAP posting.');
                            }


                            //update the updateData array and update ApplicationHistory once it is done
                            $updateData['is_posted_to_sap'] = 1;
                            $updateData['sap_response'] = json_encode($postJournalEntriesResponse ?? []);
                        }

                        // Finalize approval if it's at the maximum level
                        $application->update([
                            'status' => 3, // 3 could represent 'final approved'
                            'updated_by' => $actionBy
                        ]);
                        $updateData['status'] = 3; // Mark the history entry as final approved
                    } elseif ($applicationForwardedTo && $applicationForwardedTo['application_status'] === 3) {
                        $application->update([
                            'status' => $applicationForwardedTo['application_status'], // 3 could represent 'final approved'
                            'updated_by' => $actionBy,
                        ]);

                        $updateData['status'] = $applicationForwardedTo['application_status'];
                    }
                }

                // Update application history once everything is done accurately
                if ($applicationHistory) {
                    $applicationHistory->update($updateData);
                }

                DB::commit();

                $respString = preg_replace(
                    [
                        '/App\\\\Models\\\\/',
                        '/([a-z])([A-Z])/',
                        '/([A-Z])([A-Z][a-z])/'
                    ],
                    [
                        '',
                        '$1 $2',
                        '$1 $2'
                    ],
                    $model
                );

                try {
                    if ($updateData['status'] == 3 || $updateData['status'] == -1) {
                        $this->sendMail($applicationModel, $application, $type, $updateData['status'], []);
                    }
                    $this->sendMail($applicationModel, $application, $type, $updateData['status'], $applicationForwardedTo);
                } catch (\Exception $e) {
                    \Log::error('Error sending mail for application ID ' . $id . ': ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json(['msg_success' => 'Selected ' . Str::plural(strtolower($respString ?? 'applicaton')) . ' have been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());

            return response()->json(['msg_error' => 'An error occurred during the operation: ' . $e->getMessage()], 500);
        }
    }

    private function preparePostFields($memo, $shortName, $accountCode, $costingCode, $costingCode2, $amount, $officeLocation, $contactNo, $tax_amount = null)
    {
        if ($tax_amount) {
            return $postFields = '{
                "ReferenceDate":"' . date('Y-m-d') . '",
                "Memo": "' . $memo . '",
                "JournalEntryLines": [
                    {
                        "AccountCode": "' . $accountCode . '",
                        "CostingCode": "' . $costingCode . '",
                        "CostingCode2": "' . $costingCode2 . '",
                        "CostingCode3": "' . $officeLocation . '",
                        "Credit": 0,
                        "Debit": "' . $amount . '",
                    },
                    {
                        "ShortName": "' . $shortName . '",
                        "CostingCode": "' . $costingCode . '",
                        "CostingCode2": "' . $costingCode2 . '",
                        "CostingCode3": "' . $officeLocation . '",
                        "Credit": "' . $amount - $tax_amount . '",
                        "Debit": 0,
                    },
                    {
                        "AccountCode": "' . TAX_GL_CODE . '",
                        "CostingCode": "' . $costingCode . '",
                        "CostingCode2": "' . $costingCode2 . '",
                        "CostingCode3": "' . $officeLocation . '",
                        "Credit": "' . $tax_amount . '",
                        "Debit": 0,
                    }

                ]
            }';
        } else {
            return $postFields = '{
                            "ReferenceDate":"' . date('Y-m-d') . '",
                            "Memo": "' . $memo . '",
                            "JournalEntryLines": [
                                {
                                    "ShortName": "' . $shortName . '",
                                    "CostingCode": "' . $costingCode . '",
                                    "CostingCode2": "' . $costingCode2 . '",
                                    "U_P_NUMBER": "' . $contactNo . '",
                                    "Credit": "' . $amount . '",
                                    "Debit": 0
                                },
                                {
                                    "AccountCode": "' . $accountCode . '",
                                    "CostingCode": "' . $costingCode . '",
                                    "CostingCode2": "' . $costingCode2 . '",
                                    "Credit": 0,
                                    "Debit": "' . $amount . '"
                                }
                            ]
                        }';
        }
    }

    public function show(Request $request, $id)
    {
        $privileges = $request->instance();
        if (!(Str::startsWith($request->path(), 'approval/applications/'))) {
            $privileges['edit'] = 0;
        };
        $tab = $request->query('tab');
        $mappedModel = config('global.applications')[$request->query('tab')];
        $data = $mappedModel['name']::findOrFail($id);
        $no_of_days = 1;
        if ($request->query('tab') == 7) {
            $no_of_days = $data->estimated_travel_expenses / $data->daily_allowance;
        }
        $oldDataFlag = true;
        $travelNosString = "";
        $advanceNosString = "";
        $approvalDetail = getApplicationLogs($mappedModel['name'], $id);
        if ($tab == 9) {
            if (DsaClaimApplication::findOrFail($id)->travel_authorization_id != null) {
                $data = DsaClaimApplication::findOrFail($id);
            } else {
                $data = DsaClaimApplication::with(['dsaClaimMappings.dsaDetails'])->findOrFail($id);

                // Extract Travel Authorization IDs
                $travelNumbers = $data->dsaClaimMappings->pluck('travel_authorization_id')->filter()->toArray();

                // Extract Advance Application IDs (if they exist)
                $advanceNumbers = $data->dsaClaimMappings->pluck('advance_application_id')->filter()->toArray();

                // Fetch Travel Authorization Numbers as key-value pairs (id => travel_no)
                $travelNos = TravelAuthorizationApplication::whereIn('id', $travelNumbers)
                    ->pluck('travel_authorization_no', 'id');

                // Fetch Advance Application Numbers as key-value pairs (id => advance_no)
                $advanceNos = AdvanceApplication::whereIn('id', $advanceNumbers)
                    ->pluck('advance_no', 'id');

                // Attach both travel_authorization_no and advance_no to each dsaClaimMapping
                $data->dsaClaimMappings->transform(function ($mapping) use ($travelNos, $advanceNos) {
                    $mapping->travel_authorization_no = $travelNos[$mapping->travel_authorization_id] ?? null;
                    $mapping->advance_no = $advanceNos[$mapping->advance_application_id] ?? null;

                    $newDays = $mapping->number_of_days ?? 0; // Ensure total_days is available for each mapping
                    $DAILY_ALLOWANCE = $mapping->dsaDetails->first()->daily_allowance;
                    if ($newDays <= 15) {
                        $mapping->formula = "$DAILY_ALLOWANCE * $newDays day(s)";
                    } else {
                        $mapping->formula = "($DAILY_ALLOWANCE * 15 day(s)) + (" . ($DAILY_ALLOWANCE / 2) . " * " . ($newDays - 15) . " day(s))";
                    }
                    return $mapping;
                });

                // Now, $dsa->dsaClaimMappings contains 'travel_authorization_no' and 'advance_no' for each mapping
                $travelNosString = $travelNos->implode(', ');
                $advanceNosString = $advanceNos->implode(', ');
                $oldDataFlag = false;
            }
        }

        $approvalDetail = getApplicationLogs($mappedModel['name'], $data->id);
        // dd($approvalDetail);
        $empDetails = empDetails($data->created_by);
        $rejectRemarks = ApplicationHistory::where('application_type', $mappedModel['name'])
            ->where('application_id', $id)
            ->value('remarks'); // Assuming `reject_remarks` is the column name
        $data->reject_remarks = $rejectRemarks;
        // Pass the reject remarks to the view
        return view('approval.show', compact('data', 'tab', 'empDetails', 'approvalDetail', 'no_of_days', 'privileges', 'oldDataFlag', 'travelNosString', 'advanceNosString'));
    }

    public function edit(Request $request, $id){

        $oldDataFlag = true;
        $travelNosString="";
        $advanceNosString="";
        $travelNumbers=[];
        $da=0;

        if(DsaClaimApplication::findOrFail($id)->travel_authorization_id != null) {
            $dsa = DsaClaimApplication::findOrFail($id);
            $userId = $dsa->created_by;
            return redirect()->back()->with("msg_error", "The old DSA claim applicaitons cannot be edited");
        }else{

            $dsa = DsaClaimApplication::with(['dsaClaimMappings.dsaDetails'])->findOrFail($id);

            $userId = $dsa->created_by;
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

                    $DAILY_ALLOWANCE = $mapping->dsaDetails->first()->daily_allowance;
                    $newDays = $mapping->number_of_days ?? 0; // Ensure total_days is available for each mapping
                 // Replace with actual daily allowance from config or DB
                if ($newDays <= 15) {
                    $mapping->formula = "$DAILY_ALLOWANCE * $newDays day(s)";
                } else {
                    $mapping->formula = "($DAILY_ALLOWANCE * 15 day(s)) + (" . ($DAILY_ALLOWANCE / 2) . " * " . ($newDays - 15) . " day(s))";
                }
                return $mapping;
            });

            $job = Auth::user()->where('id', $userId)->with('empJob')->first();
            if (!$job) {
                return redirect()->back()->with('msg_error', 'You do not have a job assigned to you');
            }

            $gradeId = $job->empJob->grade->id;
            $da = DailyAllowance::whereMasGradeId($gradeId)->first();
            $da = $da->da_in_country;
            $travelNosString = $travelNos->implode(', ');
            $advanceNosString = $advanceNos->implode(', ');

            $oldDataFlag = false;
        }

        $empIdName = $job->name;
    return view('expense.dsa-approval.edit', compact('empIdName', 'da', 'dsa', 'oldDataFlag','travelNumbers', 'travelNosString', 'advanceNosString'));
    }

    public function update(Request $request, $id)
    {



        $dsaClaimApplication = DsaClaimApplication::findOrFail($id);

        // $conditionFields = approvalHeadConditionFields(DSA_CLAIM_SETTLEMENT_APPVL_HEAD, $request);
        // $approvalService = new ApprovalService();
        // $approverByHierarchy = $approvalService->getApproverByHierarchy($request->dsa_claim_type_id, \App\Models\DsaClaimType::class, $conditionFields ?? []);

        try {
            DB::beginTransaction();

            $attachments = [];


            $dsaClaimApplication->update([
                'type_id' => $request->dsa_claim_type_id,
                'travel_authorization_id' => $travel_id_json ?? null,
                'advance_application_id' => $advanceIdsJson ?? null,
                'advance_amount' => is_array($request->advance_amount) ? array_sum($request->advance_amount) : $request->advance_amount,
                'amount' => $request->amount,
                'net_payable_amount' => !is_null($request->advance_ids) ? $request->net_payable_amount : $request->amount,
                'balance_amount' => $request->balance_amount,

                'total_number_of_days' => $request->total_number_of_days,
            ]);

            $decoded_travel_auth_ids = array_map(fn($item) => json_decode($item, true), $request->travel_authorization_id);

            foreach ($decoded_travel_auth_ids as $travel_auth) {
                $taAmount = $request->ta_amount[$travel_auth['id']] ?? 0;
                $advanceAmount = $request->advance_amount[$travel_auth['id']] ?? 0;
                $total_days = $request->total_days[$travel_auth['id']] ?? 0;
                $attachment = isset($attachments[$travel_auth['id']]) ? json_encode($attachments[$travel_auth['id']]) : json_encode([]);

                DsaClaimMappings::updateOrCreate(
                    ['travel_authorization_id' => $travel_auth['id'], 'dsa_claim_id' => $dsaClaimApplication->id],
                    [
                        'advance_application_id' => $travel_auth['advance_id'] ?? null,
                        'ta_amount' => $taAmount,
                        'advance_amount' => $advanceAmount,
                        'number_of_days' => $total_days
                    ]
                );
            }

            if (isset($request->dsa_claim_detail)) {
                // Get all existing DsaClaimDetail records for the current claim
                $existingDetails = DsaClaimDetail::whereIn('dsa_map_id', function ($query) use ($dsaClaimApplication) {
                    $query->select('id')
                          ->from('dsa_claim_mappings')
                          ->where('dsa_claim_id', $dsaClaimApplication->id);
                })->get();

                // Collect incoming `dsa_map_id`s from the request
                $incomingDetailIds = [];
//dd($request->all());
                foreach ($request->dsa_claim_detail as $detail) {
                    $dsaMapping = DsaClaimMappings::where('travel_authorization_id', $detail['travel_authorization_id'])
                                                  ->where('dsa_claim_id', $dsaClaimApplication->id)
                                                  ->first();

                    if ($dsaMapping) {
                        $dsaClaimDetail = DsaClaimDetail::updateOrCreate(
                            ['dsa_map_id' => $dsaMapping->id , 'id' => (int) $detail['id']],
                            [
                                'from_date' => $detail['from_date'],
                                'from_location' => $detail['from_location'],
                                'to_date' => $detail['to_date'],
                                'to_location' => $detail['to_location'],
                                'total_days' => $detail['total_days'],
                                'daily_allowance' => $detail['daily_allowance'],
                                'total_amount' => $detail['total_amount'],
                                'travel_allowance' => $detail['travel_allowance']
                            ]
                        );
                  
                        $incomingDetailIds[] = (int) $detail['id']; // Store ID of updated/created records
                    }
                }

                // Delete records that are no longer in the request
                DsaClaimDetail::whereIn('dsa_map_id', $existingDetails->pluck('dsa_map_id'))
                              ->whereNotIn('id', $incomingDetailIds)
                              ->delete();
            }


            $applicationHistory = $dsaClaimApplication->histories->where('application_type', "App\Models\DsaClaimApplication")->where('application_id', $id)->first();
            $actionBy = auth()->id();

            // Update application status
            $dsaClaimApplication->update([

                'updated_by' => $actionBy,
            ]);

            $applicationHistory->update([
                'edited_by' => $actionBy
            ]);

            DB::commit();

            return redirect('approval/applications')->with('msg_success', 'DSA Claim/Settlement has been updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
    }

    private function sendMail($applicationModel, $applicationData, $appType, $status, $applicationForwardedTo)
    {
        // dd($applicationForwardedTo);
        $initiatorEmail = User::where('id', $applicationData['created_by'])->value('email');
        $preparedMail = prepareMail($applicationModel, $applicationData, $appType, $status);
        $initiatorMailContent = $preparedMail['initiator_mail_content'];
        if ($status == 2) {
            $initiatorMailContent .= ' verified and is in progress.';
            if (
                $applicationForwardedTo && $applicationForwardedTo['approver_details'] &&
                $applicationForwardedTo['approver_details']['user_with_approving_role']
            ) {
                Mail::to([$applicationForwardedTo['approver_details']['user_with_approving_role']->email])->send(new
                    ApprovalNotificationMail(
                        $applicationData['created_by'],
                        $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                        $applicationModel['email_subject'],
                        $preparedMail['approver_mail_content']
                    ));
            }
        } else if ($status == 3) {
            $preparedMail = prepareMail($applicationModel, $applicationData, $appType, $status);
            $initiatorMailContent .= ' approved.';


        if ($appType['name'] == 'In Country') {

            $applierId= $applicationData->created_by;
            $applier = User::where('id', $applierId)->with('empJob')->first();
            $updatedBy = User::where('id', $applicationData->updated_by)->first();
            $department = $applier->empJob->department->id;
            $roleId = 7;
            $gm = User::whereHas('empJob.department', function($query) use ($department) {
                $query->where('id', $department); // Replace with your specific department ID
            })
            ->whereHas('roles', function($query) use ($roleId) {
                $query->where('roles.id', $roleId); // Replace with your specific role ID
            })
            ->get()->first();

            $requestingUserId = $applicationData->created_by; // ID of the user who applied for the travel authorization
            $approvingUserId = $applicationData->updated_by; // ID of the user who approved the application
            $emailSubject = 'Travel Authorization Application';

            // Send the email
            try {
                Mail::to([$gm->email])->send(new TravelApprovalMail($requestingUserId, $approvingUserId, $emailSubject, $gm));

            } catch (\Exception $e) {
                log::error('Failed to send email: ' . $e->getMessage());
            }}
        } else if ($status == -1) {
            $preparedMail = prepareMail($applicationModel, $applicationData, $appType, $status);
            $initiatorMailContent .= ' rejected.';
        }
        Mail::to([$initiatorEmail])->send(new InitiatorNotificationMail(
            $applicationData['created_by'],
            $applicationModel['email_subject'] . ' Notification',
            $initiatorMailContent
        ));
    }
    public function approvedApplications(Request $request)
    {
        $privileges = $request->instance();
        $privileges['view'] = 1;
        $headers = MasApprovalHead::all();
        $user = auth()->user();
        $users = User::select('id', 'username', 'name')->whereNotIn('id', [1, 2])->get();
        $applicationModels = config('global.applications');
        $results = collect();
        $specificCondition = false;
        if ($request->is('approval/approved-applications*')) {
            // Set condition based on the path
            $statuses = [2, 3];
        } elseif ($request->is('approval/rejected-applications*')) {
            $statuses = [-1];
        }

        // Helper method to apply common query logic
        $applyQuery = function ($modelClass, $user, $request) use ($statuses) {
            return $modelClass::whereHas('audit_logs', function ($query) use ($user, $modelClass, $statuses) {
                $query->where('application_type', $modelClass)

                    ->where('action_performed_by', $user->id);
            })
                ->whereIn('status', $statuses)
                ->filter($request, false)
                ->whereYear('created_at', Carbon::now()->year)
                ->orderBy('created_at')
                ->paginate(config('global.pagination'))
                ->withQueryString();
        };

        foreach ($applicationModels as $key => $model) {
            $modelClass = $model['name'];
            $data = $applyQuery($modelClass, $user, $request);

            if ($request->is('approval/approved-applications*')) {
                // Set condition based on the path

                $data->getCollection()->transform(function ($item) {
                    $item->status = 3; // Change status to 3
                    return $item;
                });
            } elseif ($request->is('approval/rejected-applications*')) {
                $data->getCollection()->transform(function ($item) use ($modelClass) {
                    $item->reject_remarks = ApplicationHistory::where('application_type', $modelClass)
                        ->where('application_id', $item->id)
                        ->value('remarks'); // Fetch the reject_remarks
                    $item->approvalDetails = getApplicationLogs($modelClass, $item->id);
                    return $item;
                });
            }

            $results->put($key, $data);
        }
        $holidays;
        if ($results->get(7)) {
            $holidays = DB::table('work_holiday_lists')
                ->select('start_date', 'end_date')
                ->get();
        }


        return view('approval.index', compact('privileges', 'headers', 'results', 'users', 'holidays'));
    }
}
