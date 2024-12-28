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
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    protected $sap;

    public function __construct(ApiController $sap)
    {
        $this->middleware('permission:approval/applications,view')->only('index', 'approveReject', 'show');
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
        }

        return view('approval.index', compact('privileges', 'headers', 'results'));
    }

    /**
     * Handle the incoming request.
     */
    public function approveReject(Request $request)
    {

        $applicationModel = config('global.applications')[$request->item_type_id];
        $model = $applicationModel['name'];
        //getting relevant Email Subject
        $emailSubject = "";
        if (preg_match('/([^\\\\\/]+)Application$/', $model, $matches)) {
            $emailSubject = $matches[1];
        }

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

                $costingCode = null;
                $type = $application->type;
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
                    $shortName = $application->employee->username = "E00993";
                    $amount = $application->amount;
                    $tax_amount = $application->tax_amount ?? null;
                    $postToSap = $type->post_to_sap;
                    $costingCode2 = null;

                    if ($postToSap && ($accountCode && $shortName && $amount)) {
                        // Post to SAP after final Approval

                        $postFields = $this->preparePostFields($memo, $shortName, $accountCode, $costingCode, $costingCode2, $amount, $tax_amount);

                        Log::debug($postFields);

                        $postJournalEntriesResponse = $this->sap->postJournalEntries($postFields);
                        $statusCode = $postJournalEntriesResponse->getStatusCode();
                        $postJournalEntriesResponse = json_decode($postJournalEntriesResponse->getContent(), true);

                        if ($statusCode != 201) {
                            throw new \Exception('SAP Error - ' . $postJournalEntriesResponse['msg_error'] ?? 'Unknown error during SAP posting.');
                        }
                    }

                    // Finalize approval if it's at the maximum level
                    $application->update([
                        'status' => 3, // 3 could represent 'final approved'
                        'updated_by' => $actionBy,
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

            $updateData['sap_response'] = json_encode($postJournalEntriesResponse ?? []);

            // Update application history
            if ($applicationHistory) {
                $applicationHistory->update($updateData);
            }
            // dd($type);
            DB::commit();

            $model = preg_replace(
                ['/App\\\\Models\\\\/', '/([a-z])Application/'],
                ['', '$1 Application'],
                $model
            );
            $updateData['sap_response'] = json_encode($postJournalEntriesResponse ?? []);
            // Update application history
            if ($applicationHistory) {
                $applicationHistory->update($updateData);
            }
        }
        //sent email to approver as well as to initiator
        // if($updateData['status'] == 2){
        //     // $emailContent = 'has submitted a leave request and is awaiting your approval for ' . $request->no_of_days . ' days from ' . $request->from_date . ' to ' . $request->to_date . '.';
        //     $this->sentMail($emailSubject, $application, $type, $updateData['status'], $applicationForwardedTo);
        //     // Mail::to([$applicationForwardedTo['approver_details']['user_with_approving_role']->email])->send(new ApprovalNotificationMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $subject));
        // }else if($updateData['status'] == 3){
        //     $this->sentMail($emailSubject, $application, $type, $updateData['status']);
        // }else{
        //     $this->sentMail($emailSubject, $application, $type, $updateData['status']);
        // }

        return response()->json(['msg_success' => 'Selected ' . Str::plural(strtolower($model)) . ' have been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());

            return response()->json(['msg_error' => 'An error occurred during the operation: ' . $e->getMessage()], 500);
        }
    }

    private function preparePostFields($memo, $shortName, $accountCode, $costingCode, $costingCode2, $amount, $tax_amount = null)
    {
        if ($tax_amount) {
            return $postFields = '{
                "ReferenceDate":"' . date('Y-m-d') . '",
                "Memo": "' . $memo . '",
                "JournalEntryLines": [
                    {
                        "AccountCode": "' . $accountCode . '",
                        "CostingCode": "' . $costingCode . '", // department
                        "CostingCode2": "' . $costingCode2 . '",
                        "Credit": 0,
                        "Debit": "' . $amount . '"
                    },
                    {
                        "ShortName": "' . $shortName . '",
                        "CostingCode": "' . $costingCode . '", // department
                        "CostingCode2": "' . $costingCode2 . '",
                        "Credit": "' . $amount - $tax_amount . '",
                        "Debit": 0
                    },
                    {
                        "AccountCode": "' . TAX_GL_CODE . '",
                        "CostingCode": "' . $costingCode . '", // department
                        "CostingCode2": "' . $costingCode2 . '",
                        "Credit": "' . $tax_amount . '",
                        "Debit": 0
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
                                    "CostingCode": "' . $costingCode . '", // department
                                    "CostingCode2": "' . $costingCode2 . '",
                                    "Credit": "' . $amount . '",
                                    "Debit": 0
                                },
                                {
                                    "AccountCode": "' . $accountCode . '",
                                    "CostingCode": "' . $costingCode . '", // department
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
        $tab = $request->query('tab');
        $mappedModel = config('global.applications')[$request->query('tab')];
        $data = $mappedModel['name']::findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\ExpenseApplication::class, $data->id);
        $empDetails = empDetails($data->created_by);
            return view('approval.show', compact('data', 'tab', 'empDetails','approvalDetail'));
        }

    private function sentMail($emailSubject, $applicationData, $appType, $status, $applicationForwardedTo = null)
    {
        // dd($emailSubject, $applicationData, $appType, $status, $applicationForwardedTo);
        // mail to approver
        if($status == 2){
            $this->prepareEmailContent();
            Mail::to([$applicationForwardedTo['approver_details']['user_with_approving_role']->email])->send(new ApprovalNotificationMail(auth()->user()->id, $applicationForwardedTo['approver_details']['user_with_approving_role']->id, $emailSubject));
        }else if($status == 3){

        }else{

        }
        // Mail::to([$applicationForwardedTo['approver_details']['user_with_approving_role']->email])->send(new ApprovalNotificationMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
        //mail to requesting user
    }

    private function prepareEmailContent()
    {

    }
}
