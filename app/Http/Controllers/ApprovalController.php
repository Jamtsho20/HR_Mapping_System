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
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\ApplicationHistory;

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
        $approvalDetail = getApplicationLogs($mappedModel['name'], $data->id);
        // dd($approvalDetail);
        $empDetails = empDetails($data->created_by);
        $rejectRemarks = ApplicationHistory::where('application_type', $mappedModel['name'])
            ->where('application_id', $id)
            ->value('remarks'); // Assuming `reject_remarks` is the column name
        $data->reject_remarks = $rejectRemarks;
        // Pass the reject remarks to the view
        return view('approval.show', compact('data', 'tab', 'empDetails', 'approvalDetail', 'no_of_days', 'privileges'));
    }

    private function sendMail($applicationModel, $applicationData, $appType, $status, $applicationForwardedTo)
    {
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
