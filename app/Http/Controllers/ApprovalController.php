<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SAP\ApiController;
use App\Models\ApprovalHead;
use App\Models\MasApprovalHead;
use App\Models\MasExpenseType;
use App\Models\MasPayHead;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $leaves = $results->get(1);
        $expenses = $results->get(2);
        $advances = $results->get(3);
        $earnedLeave = $results->get(4);
        $transferclaims = $results->get(6);
        $travelAuthorizations = $results->get(7);
        $sifas = $results->get(8);
        $dsaclaims = $results->get(9);
        

        return view('approval.index', compact('privileges', 'headers', 'expenses', 'advances','leaves','earnedLeave','transferclaims','travelAuthorizations','dsaclaims','sifas'));
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

            if(!$application) {
                continue;
            }

            $costingCode2 = null;
            if ($applicationType == 2) { // Expense
                $type = $application->type;
                $typeId = $type->id;

                if ($typeId == 5 || $typeId == 6) { // Vehicle Fuel Claim or Parking Fee
                    $costingCode2 = $application->vehicle->vehicle_no;
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

                    $type = $application->type;
                    $accountCode = $type->code ?? null;
                    $memo = $type->name ?? null;
                    $postToSap = $type->post_to_sap;
                    $employeeId = $application->employee->username = "E00993";
                    $amount = $application->amount;
                    if ($postToSap && ($accountCode && $employeeId && $amount)) {
                        // Post to SAP after final Approval

                        $postJournalEntriesResponse = $this->sap->postJournalEntries($accountCode, $employeeId, $memo, $amount, $costingCode2);
                        $statusCode = $postJournalEntriesResponse->getStatusCode();
                        $postJournalEntriesResponse = json_decode($postJournalEntriesResponse->getContent(), true);

                        if ($statusCode != 201) {
                        throw new \Exception($postJournalEntriesResponse['msg_error'] ?? 'Unknown error during SAP posting.');
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
        }

        DB::commit();

        $model = preg_replace(
            ['/App\\\\Models\\\\/', '/([a-z])Application/'],
            ['', '$1 Application'],
            $model
        );

        return response()->json(['msg_success' => 'Selected ' . Str::plural(strtolower($model)) . ' have been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());

            return response()->json(['msg_error' => 'An error occurred during the operation: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $tab = $request->query('tab');
        $mappedModel = config('global.applications')[$request->query('tab')];
        $data = $mappedModel['name']::findOrFail($id);
        $approverDetails = []; //do later on
        $empDetails = empDetails($data->created_by);
            return view('approval.show', compact('data', 'tab', 'empDetails'));
        }
       
    }

