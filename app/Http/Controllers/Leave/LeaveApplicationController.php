<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Models\EmployeeLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $leaveBalance = EmployeeLeave::where('mas_leave_type_id', $request->leave_type)->where('mas_employee_id', loggedInUser())->value('closing_balance');
        $leaveApplication = new LeaveApplication();

        //validate if attachment is required or not based on attachment required field from leave_plans_tbl
        $leavePolicy = MasLeavePolicy::with(['leavePolicyPlan', 'leaveType'])->where('mas_leave_type_id', $request->leave_type)->whereStatus(1)->first(); 
        $attachment = "";
        $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;
        $maxLeaveDays = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->max_days : 0; 
        $leaveType = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->name : '';

        if($maxLeaveDays && (int) $request->no_of_days > $maxLeaveDays){ //maximum days of leave user can apply based on leave type
            return back()->with('msg_error', 'No of days cannot exceed more than ' . $maxLeaveDays . ' days for ' . $leaveType . '.');
        }

        // check if logged in user have enough leave balance to apply for leave and if no of applied leaves is greater han leave balance
        if ($leaveBalance == 0 || (int) $request->no_of_days > $leaveBalance) {
            $msg = $leaveBalance == 0 
                ? 'You do not have any available leave balance for ' .  $leaveType . '.'
                : 'The number of days exceeds your leave balance for ' . $leaveType . '.';
            return back()->with('msg_error', $msg);
        }
        try{
            if($attachmentRequired){
                // $this->rules['attachment'] = 'required|file|mimes:jpg,png,pdf|max:2048'; //added validation based on requirement of attachment for each leave type
                $this->validate($request, [
                    'attachment' => 'required|file|mimes:pdf,jpg,png|max:2048'
                ]);
                // if (!$request->hasFile('attachment')) {
                //     // Throw an exception if the attachment is required but not provided
                //     throw new \Exception('Please upload the attachment (medical certificate produced from hospital) for ' . $leaveType . '.');
                // }
            }
            if($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = uploadImageToDirectory($file, 'images/leaves/');
            }
        }catch(\Exception $e){
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        $this->validate($request, $this->rules, $this->messages);

        try{
            DB::beginTransaction();
            $leaveApplication = new LeaveApplication();
            $leaveApplication->mas_employee_id = loggedInUser();
            $leaveApplication->mas_leave_type_id = $request->leave_type;
            $leaveApplication->from_day = $request->from_day;
            $leaveApplication->to_day = $request->to_day;
            $leaveApplication->from_date = $request->from_date;
            $leaveApplication->to_date = $request->to_date;
            $leaveApplication->no_of_days = $request->no_of_days;
            $leaveApplication->remarks = $request->remarks;
            $leaveApplication->attachment = $attachment;
            $leaveApplication->status = $request->status ?? 1;
            $leaveApplication->save();
        
            // Create a history record associated with the LeaveApplication
            $leaveApplication->histories()->create([
                'level' => 'Test Level',
                'status' => 1,
                'remarks' => $request->remarks,
                'created_by' => loggedInUser(),
            ]);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        return redirect('leave/leave-apply')->with('msg_success', 'Leave has been applied successfully!.');   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leaveApplication = LeaveApplication::findOrfail($id);
        // return view();
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
        $leaveBalance = EmployeeLeave::where('mas_leave_type_id', $request->leave_type)->where('mas_employee_id', loggedInUser())->value('closing_balance');
        $leaveApplication = new LeaveApplication();

        //validate if attachment is required or not based on attachment required field from leave_plans_tbl
        $leavePolicy = MasLeavePolicy::with(['leavePolicyPlan', 'leaveType'])->where('mas_leave_type_id', $request->leave_type)->whereStatus(1)->first(); 
        $attachment = "";
        $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;
        $maxLeaveDays = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->max_days : 0; 
        $leaveType = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->name : '';

        if($maxLeaveDays && (int) $request->no_of_days > $maxLeaveDays){ //maximum days of leave user can apply based on leave type
            return back()->with('msg_error', 'No of days cannot exceed more than ' . $maxLeaveDays . ' days for ' . $leaveType . '.');
        }

        // check if logged in user have enough leave balance to apply for leave and if no of applied leaves is greater han leave balance
        if ($leaveBalance == 0 || (int) $request->no_of_days > $leaveBalance) {
            $msg = $leaveBalance == 0 
                ? 'You do not have any available leave balance for ' .  $leaveType . '.'
                : 'The number of days exceeds your leave balance for ' . $leaveType . '.';
            return back()->with('msg_error', $msg);
        }
        try{
            if($attachmentRequired && $leaveApplication->attachment !== null){
                $this->validate($request, [
                    'attachment' => 'required|file|mimes:pdf,jpg,png|max:2048'
                ]);
            }
            if($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = uploadImageToDirectory($file, 'images/leaves/');
            }
        }catch(\Exception $e){
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        $this->validate($request, $this->rules, $this->messages);
        try{
            DB::beginTransaction();
            $leaveApplication->update([
                'mas_employee_id' => $leaveApplication->mas_employee_id,
                'mas_leave_type_id' => $request->leave_type,
                'from_day' => $request->from_day,
                'to_day' => $request->to_day,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'no_of_days' => $request->no_of_days,
                'remarks' => $request->remarks,
                'attachment' => $leaveApplication->attachment ?? null,
                'status' => $leaveApplication->status,
            ]);
        
            // Create a history record associated with the LeaveApplication
            $leaveApplication = $leaveApplication->fresh();
            if(!$leaveApplication){
                throw new \Exception("Leave application not found!");
            }
            $leaveApplication->histories()->create([
                'level' => 'Test Level',
                'status' => $leaveApplication->status,
                'remarks' => $request->remarks,
                'created_by' => $leaveApplication->created_by,
                'updated_by' => loggedInUser()
            ]);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
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
        $balances = EmployeeLeave::filter($request)->with(['employee', 'leaveType'])->where('mas_employee_id', auth()->user()->id)->paginate(30);
        return view('leave.leave.leave-balance', compact('balances', 'leaveTypes'));
    }

    public function leaveEncashment(){
        return view('leave.leave.leave-encashment');
    }
}
