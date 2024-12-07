<?php

namespace App\Http\Controllers\Api\Leave;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\LeaveApplication;
use App\Models\MasEmployeeJob;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;

class LeaveApprovalController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    protected $rules = [
        'leave_type' => 'required',
        'from_day' => 'required',
        'to_day' => 'required',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
        'no_of_days' => 'required',
    ];

    protected $messages = [];

    private $attachmentPath = 'images/leaves/';

    public function index(Request $request)
    {
        try{
        $user = auth()->user();
        // $historyData = ApplicationHistory::whereHas('application', function ($query) {
        //     $query->where('application_type', 'App\Models\LeaveApplication'); // Assuming you store this class in 'application_type' column
        // })->where('approver_emp_id', $user->id)
        //   ->get();
        $leaveTypes = MasLeaveType::get(['id', 'name']);
        $leaves = LeaveApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', \App\Models\LeaveApplication::class);
        })
            ->whereNotIn('status', [-1, 3])
            ->filter($request, false) //sent onesOenRecord parameter as flase as it need to fetch all despites of authenticated user
            ->orderBy('created_at')
            ->get();
            return $this->successResponse($leaves, 'Leave approvals application fetched successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

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
            $leave = LeaveApplication::findOrFail($id);
            $empDetails = empDetails($leave->created_by);
            return response()->json(['leave' => $leave, 'empDetails' => $empDetails]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
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
        $leaveTypes = MasLeaveType::get(['id', 'name']);
        $leave = LeaveApplication::findOrfail($id);
        return view('leave.approval.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { //need to writ code if image or file already exists then first insert the new one the delete the existing from application folder
        $leaveApplication = LeaveApplication::findOrFail($id);
        $result = $this->handleLeaveApplication($request, $leaveApplication);
        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }
        try {
            $this->validate($request, $this->rules, $this->messages);

            DB::beginTransaction();
            $leaveApplication->update([
                'mas_leave_type_id' => $request->leave_type,
                'from_day' => $request->from_day,
                'to_day' => $request->to_day,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'no_of_days' => $request->no_of_days,
                'remarks' => $request->remarks,
                'attachment' => $result['attachment'],
                'status' => $leaveApplication->status,
            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect('leave/approval')->with('msg_success', 'Leave has been updated successfully!.');
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

    private function handleLeaveApplication(Request $request, $leaveApplication = null)
    { //common function to handle store and update of leave

        $leaveBalance = EmployeeLeave::where('mas_leave_type_id', $request->leave_type)
            ->where('mas_employee_id', $leaveApplication->created_by)
            ->value('closing_balance');


        $empJobDetail = MasEmployeeJob::where('mas_employee_id', $leaveApplication->created_by)->first(); // query to fetch employee grade step

        // query to fetch leave policy details
        $leavePolicy = MasLeavePolicy::with(['leavePolicyPlan.leavePolicyRule' => function ($query) use ($empJobDetail) {
            $query->where('mas_grade_step_id', $empJobDetail->mas_grade_step_id)->whereStatus(1);
        }, 'leaveType'])
            ->where('mas_leave_type_id', $request->leave_type)
            ->whereStatus(1)
            ->first();


        $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;
        $maxLeaveDays = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->max_days : 0;
        $leaveType = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->name : '';

        //validation based on leave policy rule(at once how many days/months/years based on uom emp can apply)
        if ($leavePolicy && $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->duration < $request->no_of_days) {
            $duration = $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->duration;
            $uom = $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->uom;
            $unit = match ($uom) {
                3 => 'years',
                2 => 'months',
                default => 'days',
            };
            return back()->withInput()->with('msg_error', 'You cannot apply more than ' . $duration . ' ' . $unit . ' in a row for ' . $leaveType . '.');
        }
        //validation based on employment type
        if ($leavePolicy && $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->mas_employment_type_id !== 1) {
            if ($leavePolicy && ($leavePolicy->leavePolicyPlan->leavePolicyRule[0]->mas_employment_type_id !== $empJobDetail->mas_employment_type_id)) {
                return back()->withInput()->with('msg_error', 'You are not eligible to apply '  . $leaveType . ', for further information please contact system admin.');
            }
        }


        // Check leave balance
        if ($leaveBalance == 0 || (int) $request->no_of_days > $leaveBalance) {
            $msg = $leaveBalance == 0
                ? 'You do not have any available leave balance for ' .  $leaveType . '.'
                : 'The number of days exceeds your leave balance for ' . $leaveType . '.';
            return back()->withInput()->with('msg_error', $msg);
        }


        if ($request->hasFile('attachment')) {
            // Check if there is an existing file and delete it
            if ($leaveApplication && $leaveApplication->attachment) {
                $existingFilePath = public_path($leaveApplication->attachment);
                if (file_exists($existingFilePath) && is_file($existingFilePath)) {
                    unlink($existingFilePath); // Delete the existing file
                }
            }

            // Upload the new file and save the path
            $file = $request->file('attachment');
            $path = uploadImageToDirectory($file, $this->attachmentPath); // Ensure this function generates a relative path
            $validatedData['attachment'] = $path; // Save the relative path
        } else {
            // If no new file is uploaded, keep the existing attachment path
            $validatedData['attachment'] = $leaveApplication ? $leaveApplication->attachment : ''; // Maintain existing or set to empty if none
        }

        // Return the updated data or response as needed
        return [
            'leaveBalance' => $leaveBalance,
            'maxLeaveDays' => $maxLeaveDays,
            'leaveType' => $leaveType,
            'attachment' => $validatedData['attachment']
        ];
    }
}

