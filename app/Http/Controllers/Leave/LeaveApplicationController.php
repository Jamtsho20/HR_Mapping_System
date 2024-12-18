<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Models\EmployeeLeave;
use App\Models\MasEmployeeJob;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ApplicationForwardedMail;
use Illuminate\Support\Facades\Mail;
use App\Services\ApplicationHistoriesService;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:leave/leave-apply,view')->only('index', 'show', 'leaveBalance');
        $this->middleware('permission:leave/leave-apply,create')->only('create');
        $this->middleware('permission:leave/leave-apply,edit')->only('update');
        $this->middleware('permission:leave/leave-apply,delete')->only('destroy');
    }

    protected $rules = [
        'leave_type' => 'required',
        'from_day' => 'required',
        'to_day' => 'required',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
        'no_of_days' => 'required',
    ];

    protected $messages = [

    ];

    private $attachmentPath = 'images/leaves/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $privileges = $request->instance();
        $leaveTypes = MasLeaveType::get(['id', 'name']);
        $leaveApplications = LeaveApplication::filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
        // dd($leaveApplications);

        return view('leave.leave.index',compact('privileges','leaveTypes', 'leaveApplications'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leaveTypes = MasLeaveType::get(['id', 'name']);
          return view('leave.leave.create',compact('leaveTypes')); // Ensure the view name is correct
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->handleLeaveApplication($request);
        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }

        $this->validate($request, $this->rules, $this->messages);
        $conditionFields = approvalHeadConditionFields(LEAVE_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->leave_type, \App\Models\MasLeaveType::class, $conditionFields ?? []);
        $matchingLeaves = prepareLeaveCombination(Carbon::parse($request->from_date));
        if ($request->leave_type == CASUAL_LEAVE && $matchingLeaves->count() == 2) {
            if ($matchingLeaves[0]->type_id == EARNED_LEAVE && $matchingLeaves[1]->type_id == CASUAL_LEAVE) {
                return back()->withInput()->with('msg_error', 'Leave combination of CL + EL + CL, last CL is not allowed. Please correct & try again.');
            }
        }
        try {
            DB::beginTransaction();
            
            $leaveApplication = LeaveApplication::create([
                'type_id' => $request->leave_type,
                'from_day' => $request->from_day,
                'to_day' => $request->to_day,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'no_of_days' => $request->no_of_days,
                'remarks' => $request->remarks,
                'attachment' => $result['attachment'],
                'status' => $approverByHierarchy['application_status'],
            ]);
            // Create a history record
            $historyService = new ApplicationHistoriesService();
            // if this leave combination El + CL + EL happens then middle CL will be converted to EL and accordingly update data and update leave balance accordingly
            if($request->leave_type == EARNED_LEAVE && $matchingLeaves->count() == 2){
                if ($matchingLeaves[0]->type_id == CASUAL_LEAVE && $matchingLeaves[1]->type_id == EARNED_LEAVE) {
                    DB::table('leave_applications')->where('id', $matchingLeaves[0]->id)->update(['type_id' => 2]);
                    DB::table('application_histories')
                        ->where('application_type', \App\Models\MasLeaveType::class)
                        ->where('application_id', $matchingLeaves[0]->id)
                        ->update([
                            'hierarchy_id' => $approverByHierarchy['hierarchy_id'],
                            'max_level_id' => $approverByHierarchy['max_level_id'],
                            'next_level_id' => $approverByHierarchy['next_level']->id,
                            'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'],
                            'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $approverByHierarchy['next_level']->sequence,
                        ]);
                    
                }
            }
            $historyService->saveHistory($leaveApplication->histories(), $approverByHierarchy, $request->remarks);

            // Fetch the approver dynamically using ApprovalService and sent email to notify approver accordingly
            DB::commit();
            if(isset($approverByHierarchy['approver_details'])){
                $emailContent = 'has submitted a leave request and is awaiting your approval for ' . $request->no_of_days . ' days from ' . $request->from_date . 'to' . $request->to_date . '.';
                $emailSubject = 'Leave Application';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect('leave/leave-apply')->with('msg_success', 'Leave has been applied successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leave = LeaveApplication::findOrfail($id);
        $empDetails = empDetails($leave->created_by);

        return view('leave.leave.show', compact('leave', 'empDetails'));
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
        return view('leave.leave.edit', compact('leave', 'leaveTypes'));
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
                // 'mas_employee_id' => $leaveApplication->mas_employee_id,
                'type_id' => $request->leave_type,
                'from_day' => $request->from_day,
                'to_day' => $request->to_day,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'no_of_days' => $request->no_of_days,
                'remarks' => $request->remarks,
                'attachment' => $result['attachment'],
                'status' => $leaveApplication->status,
            ]);

            // this will be inserted to application audit history table
            // $leaveApplication->histories()->create([
            //     'level' => 'Test Level',
            //     'status' => $leaveApplication->status,
            //     'remarks' => $request->remarks,
            //     'created_by' => $leaveApplication->created_by,
            //     'updated_by' => loggedInUser()
            // ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect('leave/leave-apply')->with('msg_success', 'Leave has been updated successfully!.');
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
            LeaveApplication::findOrFail($id)->delete();

            return back()->with('msg_success', 'Leave Application has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Leave Application cannot be delete as it has been forwarded to higher authorities. For further information contact system admin.');
        }
    }

    public function leaveBalance(Request $request){
        $leaveTypes = MasLeaveType::get(['id', 'name']);
        $balances = EmployeeLeave::filter($request)->with(['employee', 'leaveType'])->where('mas_employee_id', auth()->user()->id)->paginate(config('global.pagination'));
        return view('leave.leave.leave-balance', compact('balances', 'leaveTypes'));
    }

    private function handleLeaveApplication(Request $request, $leaveApplication = null){ //common function to handle store and update of leave
        $leaveBalance = EmployeeLeave::where('mas_leave_type_id', $request->leave_type)
            ->where('mas_employee_id', loggedInUser())
            ->value('closing_balance');

        $empJobDetail = MasEmployeeJob::where('mas_employee_id', loggedInUser())->first(); // query to fetch employee grade step

        // query to fetch leave policy details
        $leavePolicy = MasLeavePolicy::with(['leavePolicyPlan.leavePolicyRule' => function($query) use($empJobDetail) {
            $query->where('mas_grade_step_id', $empJobDetail->mas_grade_step_id)->whereStatus(1);
        }, 'leaveType'])
            ->where('type_id', $request->leave_type)
            ->whereStatus(1)
            ->first();

        $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;
        $maxLeaveDays = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->max_days : 0;
        $leaveType = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->name : ''; 

        //validation based on leave policy rule(at once how many days/months/years based on uom emp can apply)
        // if ($leavePolicy && $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->duration < $request->no_of_days) {
        //     $duration = $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->duration;
        //     $uom = $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->uom;
        //     $unit = match($uom) {
        //         3 => 'years',
        //         2 => 'months',
        //         default => 'days',
        //     };
        //     return back()->withInput()->with('msg_error', 'You cannot apply more than ' . $duration . ' ' . $unit . ' in a row for ' . $leaveType . '.');
        // }
        //validation based on employment type
        if ($leavePolicy && $leavePolicy->leavePolicyPlan->leavePolicyRule[0]->mas_employment_type_id !== 1) {
            if($leavePolicy && ($leavePolicy->leavePolicyPlan->leavePolicyRule[0]->mas_employment_type_id !== $empJobDetail->mas_employment_type_id)){
                return back()->withInput()->with('msg_error', 'You are not eligible to apply '  . $leaveType . ', for further information please contact system admin.');
            }
        }
        // Check for max leave days commented for now
        // if ($maxLeaveDays && (int) $request->no_of_days > $maxLeaveDays) {
        //     return back()->with('msg_error', 'No of days cannot exceed more than ' . $maxLeaveDays . ' days for ' . $leaveType . '.');
        // }

        // Check leave balance
        if ($leaveBalance == 0 || (int) $request->no_of_days > $leaveBalance) {
            $msg = $leaveBalance == 0
                ? 'You do not have any available leave balance for ' .  $leaveType . '.'
                : 'The number of days exceeds your leave balance for ' . $leaveType . '.';
            return back()->withInput()->with('msg_error', $msg);
        }

        // Handle file upload if required based on defined in leave policy(old code)
        // $attachment = $leaveApplication ? $leaveApplication->attachment : '';
        // if ($attachmentRequired && !$attachment) {
        //     $this->validate($request, [
        //         'attachment' => 'required|file|mimes:pdf,jpg,png|max:2048'
        //     ]);
        // }
        // if ($request->hasFile('attachment')) {
        //     $file = $request->file('attachment');
        //     if ($leaveApplication && $leaveApplication->attachment && file_exists(public_path($this->attachmentPath . $leaveApplication->attachment))) {
        //         delete_image($this->attachmentPath . $leaveApplication->attachment); // Delete old attachment
        //     }
        //     $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        // }

        // return [
        //     'leaveBalance' => $leaveBalance,
        //     'maxLeaveDays' => $maxLeaveDays,
        //     'leaveType' => $leaveType,
        //     'attachment' => $attachment
        // ];
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
