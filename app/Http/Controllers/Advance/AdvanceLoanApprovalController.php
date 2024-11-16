<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdvanceLoanApprovalController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:advance-loan/advance-loan-approval,view')->only('index');
        $this->middleware('permission:advance-loan/advance-loan-approval,create')->only('store');
        $this->middleware('permission:advance-loan/advance-loan-approval,edit')->only('update');
        $this->middleware('permission:advance-loan/advance-loan-approval,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $user = auth()->user();

        // Fetch advance loan applications with histories where the approver matches the current user
        $advances = AdvanceApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', 'App\Models\AdvanceApplication');
        })->whereNotIn('status', [-1, 3]) // Exclude rejected and canceled applications
            ->filter($request, false)
            ->orderBy('created_at')
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return view('advance-loan.approval.index', compact('privileges', 'advances'));
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

        DB::beginTransaction();
        try {
            $approvalService = new ApprovalService();

            foreach ($itemIds as $id) {
                $advanceApplication = AdvanceApplication::findOrFail($id);
                $applicationHistory = $advanceApplication->histories
                    ->where('application_type', AdvanceApplication::class)
                    ->where('application_id', $id)
                    ->first();

                // Update advance loan application status
                $advanceApplication->update([
                    'status' => $status,
                    'updated_by' => $userId,
                ]);

                // Prepare data for forwarding application if approved
                $updateData = [
                    'status' => $status,
                    'remarks' => $rejectRemarks,
                    'action_performed_by' => $userId,
                ];

                if ($action === 'approve' && $applicationHistory) {
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, AdvanceApplication::class);

                    if ($applicationForwardedTo && isset($applicationForwardedTo['next_level'])) {
                        $updateData = array_merge($updateData, [
                            'level_id' => $applicationForwardedTo['next_level']->id,
                            'approver_role_id' => $applicationForwardedTo['approver_details']['approver_role_id'],
                            'approver_emp_id' => $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $applicationForwardedTo['next_level']->sequence,
                        ]);
                    } elseif ($applicationForwardedTo && isset($applicationForwardedTo['status']) && $applicationForwardedTo['status'] === 'max_level_reached') {
                        // Finalize approval if it's at the maximum level
                        $advanceApplication->update([
                            'status' => 3, // 3 could represent 'final approved' for advance loans as well
                            'updated_by' => $userId,
                        ]);
                        $updateData['status'] = 3; // Mark the history entry as final approved
                    }
                }

                // Update application history
                if ($applicationHistory) {
                    $applicationHistory->update($updateData);
                }
            }

            DB::commit();
            return response()->json(['message' => 'All advance loans have been successfully processed.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk approval/rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during the operation.'], 500);
        }
    }
}
