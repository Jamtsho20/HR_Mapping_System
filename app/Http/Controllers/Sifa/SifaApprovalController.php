<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Models\SifaDocument;
use App\Models\SifaRegistration;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SifaApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sifa/sifa-approval,view')->only('index','show');
        $this->middleware('permission:sifa/sifa-approval,create')->only('store');
        $this->middleware('permission:sifa/sifa-approval,edit')->only('update', 'bulkApprovalRejection');
        $this->middleware('permission:sifa/sifa-approval,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
     $user=auth()->user();
        // $sifaRegistrations = SifaRegistration::where('mas_employee_id', auth()->id())->first();

        // Fetch sifa applications with histories where the approver matches the current user
        $sifas = SifaRegistration::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', 'App\Models\SifaRegistration');
        })->whereNotIn('status', [-1, 3]) // Exclude rejected and canceled applications
            //->filter($request, false)
            ->orderBy('created_at')
           // ->paginate(config('global.pagination'))
            ->get();

        return view('sifa.sifa-approval.index', compact('privileges','sifas'));
    }

    public function update ()
    {
        // dd("a");
    }
    
    public function show($id, Request $request)
    {
       

        $sifaRegistration = SifaRegistration::with(['SifaNomination', 'SifaDependent', 'SifaDocument'])->findOrFail($id);
        $user = empDetails($sifaRegistration->created_by);
        $sifaDocuments = SifaDocument::where('sifa_registration_id', $id)->first();
        //dd($sifaRegistration->sifaDocument);

        return view('sifa.sifa-approval.show', compact('user', 'sifaRegistration', 'sifaDocuments'));
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
                $leaveApplication = SifaRegistration::findOrFail($id);
                $applicationHistory = $leaveApplication->histories
                    ->where('application_type', SifaRegistration::class)
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
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, SifaRegistration::class);
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
            return response()->json(['message' => 'All sifa request has been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during the operation.'], 500);
        }
    }
    
}
