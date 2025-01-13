<?php

namespace App\Http\Controllers\Api\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\AdvanceDetail;
use App\Services\ApprovalService;
use App\Models\MasAdvanceTypes;
use App\Models\BudgetCode;
use App\Models\MasDzongkhag;
use App\Models\TravelAuthorizationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;

class AdvanceLoanApprovalController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $employeeLists = employeeList();
            $currentUser = auth()->user();
            $statusParam = $request->input('status'); // E.g., 'pending', 'approved', 'rejected'
            $statuses = [];
            $applicationType = 'App\Models\AdvanceApplication'; // Default application type
            $tab = null;

            // Define conditions based on the status parameter
            switch ($statusParam) {
                case 'pending':
                    $statuses = [1, 2]; // Pending statuses
                    $tab = 'history';
                    break;
                case 'approved':
                    $statuses = [2, 3]; // Approved statuses
                    $tab = 'audit_logs';
                    break;
                case 'rejected':
                    $statuses = [-1]; // Rejected status
                    $tab = 'audit_logs'; // Adjust tab if needed
                    break;
                default:
                    return response()->json(['error' => 'Invalid status parameter'], 400);
            }

            // Build the query dynamically
            $advances = AdvanceApplication::with('advanceType:id,name')
                ->with([
                    'employee:id,name,username',
                    'employee.empjob' => function ($query) {
                        $query->select('mas_employee_id', 'mas_department_id', 'mas_section_id');
                    },
                    'employee.empjob.department:id,name',
                    'employee.empjob.section:id,name',
                    'histories:id,application_id,action_performed_by',
                ])
                ->when($tab === 'history', function ($query) use ($user, $applicationType) {
                    $query->whereHas('histories', function ($query) use ($user, $applicationType) {
                        $query->where('approver_emp_id', $user->id)
                              ->where('application_type', $applicationType);
                    });
                })
                ->when($tab === 'audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                    $query->whereHas('audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                        $query->where('application_type', $applicationType)
                              ->where('action_performed_by', $currentUser->id);
                    })
                    ->whereYear('created_at', Carbon::now()->year); // Add condition for audit_logs
                })
                ->whereIn('status', $statuses) // Filter based on statuses
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();

            return response()->json(['advances' => $advances], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }



    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $advance = AdvanceApplication::findOrFail($id);
            $advanceDetails = AdvanceDetail::where('advance_application_id', $advance->id)->get();
            $budgetCodes = BudgetCode::get();
            $dzongkhags = MasDzongkhag::get();


            $empDetails = empDetails($advance->created_by);
            return response()->json(['advance' => $advance, 'empDetails' => $empDetails, 'advanceDetails' => $advanceDetails]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
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
        $user = auth()->user();
        $advance = AdvanceApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', 'App\Models\AdvanceApplication');
        })->whereNotIn('status', [-1, 3]) // Exclude rejected and canceled applications
            ->orderBy('created_at')
            ->firstOrFail();;
        $advanceType = MasAdvanceTypes::where('id', $advance->type_id)->first(); // Fetch advance types
        $budgetCodes = BudgetCode::get();
        $dzongkhags = MasDzongkhag::get();
        $travelAuthorizations = [];
        $advanceDetails = []; // only if advance type is ADVANCE_TO_STAFF
        if($advance->type_id == DSA_ADVANCE){
            $travelAuthorizations = TravelAuthorizationApplication::with('details')->where('created_by', loggedInUser())
                                        ->where('id', $advance->travel_authorization_id)
                                        ->first();
        }
        if($advance->type_id == ADVANCE_TO_STAFF){
            $advanceDetails = AdvanceDetail::where('advance_application_id', $advance->id)->get();
        }
        $redirectUrl = 'advance-loan/advance-loan-approval';


        return view('advance-loan.apply.edit', compact( 'redirectUrl','advance', 'advanceType', 'travelAuthorizations', 'budgetCodes', 'dzongkhags', 'advanceDetails'));
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
            AdvanceApplication::findOrFail($id)->delete();

            return back()->with('msg_success', 'Advance Applicaton has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Advance Applicaton cannot be deleted as it is used by other modules.');
        }
    }

    public function bulkApprovalRejection(Request $request)
    {
        $action = $request->action;
        $itemIds = $request->item_ids;
        $status = ($action === 'approve') ? 2 : -1;
        $rejectRemarks = $request->input('reject_remarks', '');
        $userId = auth()->id();
        $responseMessage = $action === 'approve' ? 'approved.' : 'rejected.';
        // dd($itemIds);
        DB::beginTransaction();
        try {
            $approvalService = new ApprovalService();

            foreach ($itemIds as $id) {
                $leaveApplication = AdvanceApplication::findOrFail($id);
                $applicationHistory = $leaveApplication->histories
                    ->where('application_type', AdvanceApplication::class)
                    ->where('application_id', $id)
                    ->first();

                // Update leave application status
                $leaveApplication->update([
                    'status' => $status,
                    'updated_by' => $userId,
                ]);

                // Forward application if approved
                $updateData = [
                    'status' => $status,
                    'remarks' => $rejectRemarks,
                    'action_performed_by' => $userId,
                ];

                if ($action === 'approve' && $applicationHistory) {
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, AdvanceApplication::class);
                    // dd($applicationForwardedTo);
                    if ($applicationForwardedTo && isset($applicationForwardedTo['next_level'])) {
                        $updateData = array_merge($updateData, [
                            'level_id' => $applicationForwardedTo['next_level']->id,
                            'approver_role_id' => $applicationForwardedTo['approver_details']['approver_role_id'],
                            'approver_emp_id' => $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $applicationForwardedTo['next_level']->sequence,
                        ]);
                        // Attempt to send email to next approver need to work on it
                        // try {
                        //     Mail::to($nextApprover->email)->send(new NextApproverNotificationMail($leaveApplication, $nextApprover));
                        // } catch (\Exception $e) {
                        //     \Log::error('Failed to send email to next approver: ' . $e->getMessage());
                        // }
                    } elseif ($applicationForwardedTo && isset($applicationForwardedTo['application_status']) && $applicationForwardedTo['application_status'] === 'max_level_reached') {
                        // Finalize approval if it's at the maximum level
                        $leaveApplication->update([
                            'status' => 3, // 3 could represent 'final approved'
                            'updated_by' => $userId,
                        ]);
                        $updateData['status'] = 3; // Mark the history entry as final approved
                    } elseif ($applicationForwardedTo && $applicationForwardedTo['application_status'] === 3) {
                        $leaveApplication->update([
                            'status' => $applicationForwardedTo['application_status'], // 3 could represent 'final approved'
                            'updated_by' => $userId,
                        ]);
                        $updateData['status'] = $applicationForwardedTo['application_status'];
                    }
                }
                // Update application history
                if ($applicationHistory) {
                    $applicationHistory->update($updateData);
                }

                // Attempt to send email to applicant about the approval/rejection status need to work on it
                // try {
                //     Mail::to($user->email)->send(new LeaveApplicationStatusMail($leaveApplication, $action, $rejectRemarks));
                // } catch (\Exception $e) {
                //     \Log::error('Failed to send email to applicant: ' . $e->getMessage());
                // }
            }

            DB::commit();
            return response()->json(['message' => 'All leave has been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk approval/rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during the operation.'], 500);
        }
    }
}
