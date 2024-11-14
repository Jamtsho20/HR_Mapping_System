<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:leave/approval,view')->only('index', 'show');
        $this->middleware('permission:leave/approval,create')->only('store');
        $this->middleware('permission:leave/approval,edit')->only('update', 'bulkApprovalRejection');
        $this->middleware('permission:leave/approval,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $user = auth()->user();
        // $historyData = ApplicationHistory::whereHas('application', function ($query) {
        //     $query->where('application_type', 'App\Models\LeaveApplication'); // Assuming you store this class in 'application_type' column
        // })->where('approver_emp_id', $user->id)
        //   ->get();
        $leaves = LeaveApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', 'App\Models\LeaveApplication');
        })->whereNotIn('status', [-1, 3])->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
        return view('leave.approval.index', compact('privileges', 'leaves'));
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
                $leaveApplication = LeaveApplication::findOrFail($id);
                $applicationHistory = $leaveApplication->histories
                    ->where('application_type', LeaveApplication::class)
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
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, LeaveApplication::class);
                    // dd($applicationForwardedTo);
                    if ($applicationForwardedTo && isset($applicationForwardedTo['next_level'])) {
                        $updateData = array_merge($updateData, [
                            'level_id' => $applicationForwardedTo['next_level']->id,
                            'approver_role_id' => $applicationForwardedTo['approver_details']['approver_role_id'],
                            'approver_emp_id' => $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $applicationForwardedTo['next_level']->sequence,
                        ]);
                    }elseif ($applicationForwardedTo && isset($applicationForwardedTo['status']) && $applicationForwardedTo['application_status'] === 'max_level_reached') {
                        // Finalize approval if it's at the maximum level
                        $leaveApplication->update([
                            'status' => 3, // 3 could represent 'final approved'
                            'updated_by' => $userId,
                        ]);
                        $updateData['status'] = 3; // Mark the history entry as final approved
                    }elseif ($applicationForwardedTo && $applicationForwardedTo['application_status'] === 3){
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
            }

            DB::commit();
            return response()->json(['message' => 'All leave has been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during the operation.'], 500);
        }
    }
}
