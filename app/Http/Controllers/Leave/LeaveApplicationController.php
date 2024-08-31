<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
      
    public function __construct()
    {
        $this->middleware('permission:leave/leave-apply,view')->only('index', 'show');
        $this->middleware('permission:leave/leave-apply,create')->only('create');
        $this->middleware('permission:leave/leave-apply,edit')->only('update');
        $this->middleware('permission:leave/leave-apply,delete')->only('destroy');
    }

    protected $rules = [
        'mas_employee_id' => 'required',
        'mas_leave_type_id' => 'required',
        'from_day' => 'required',
        'to_day' => 'required',
        'from_date' => 'required|date',
        'to_date' => 'required|date',
        'no_of_days' => 'required',
        'attachment' => 'file|mimes:jpg,png,pdf|max:2048'
    ];

    protected $messages = [
        'mas_employee_id.required' => 'Employee field is required.',
        'mas_leave_type_id.required' => 'Leave Type field is required.'
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
        $leaveApplication = LeaveApplication::filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();

        return view('leave.leave.index',compact('privileges','leaveTypes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leaveTypes = MasLeaveType::get(['id', 'name']); 
        return view('leave.leave.apply-leave',compact('leaveTypes')); // Ensure the view name is correct
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        $leaveApplication = new LeaveApplication();
        //validate if attachment is required or not based on attachment required field from leave_plans_tbl
        $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')->where('mas_leave_type_id', $request->mas_leave_type_id)->get(); 
        $attachment = "";
        $attachmentRequired = $leavePolicy->leavePolicyPlan[0]->attachment_required;
        try{
            if($attachmentRequired){
                // $this->rules['attachment'] = 'required|file|mimes:jpg,png,pdf|max:2048';
                if (!$request->hasFile('attachment')) {
                    // Throw an exception if the attachment is required but not provided
                    throw new \Exception('Please upload the attachment (medical certificate produced from hospital).');
                }
            }
            if($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = uploadImageToDirectory($file, 'images/leaves/');
            }
        }catch(\Exception $e){
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        
        $leaveApplication->mas_employee_id = $request->mas_employee_id;
        $leaveApplication->mas_leave_type_id = $request->mas_leave_type_id;
        $leaveApplication->from_day = $request->from_day;
        $leaveApplication->to_day = $request->to_day;
        $leaveApplication->from_date = $request->from_date;
        $leaveApplication->to_date = $request->to_date;
        $leaveApplication->no_of_days = $request->no_of_days;
        $leaveApplication->remarks = $request->remarks;
        $leaveApplication->attachment = $attachment;
        $leaveApplication->status = $request->status;
        $leaveApplication->save();
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
        $leaveApplication = LeaveApplication::findOrfail($id);
        // return view('')
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
        //validate if attachment is required or not based on attachment required field from leave_plans_tbl
        $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')->where('mas_leave_type_id', $request->mas_leave_type_id)->get(); 
        $attachment = "";
        $attachmentRequired = $leavePolicy->leavePolicyPlan[0]->attachment_required;
        try{
            if($attachmentRequired){
                if (!$request->hasFile('attachment')) {
                    // Throw an exception if the attachment is required but not provided
                    throw new \Exception('Please upload the attachment (medical certificate produced from hospital).');
                }
            }
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = uploadImageToDirectory($file, 'images/leaves/');
            }
        }catch(\Exception $e){
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        $this->validate($request, $this->rules, $this->messages);
        $leaveApplication = LeaveApplication::findOrfail($id);
        $leaveApplication->mas_employee_id = $request->mas_employee_id;
        $leaveApplication->mas_leave_type_id = $request->mas_leave_type_id;
        $leaveApplication->from_day = $request->from_day;
        $leaveApplication->to_day = $request->to_day;
        $leaveApplication->from_date = $request->from_date;
        $leaveApplication->to_date = $request->to_date;
        $leaveApplication->no_of_days = $request->no_of_days;
        $leaveApplication->remarks = $request->remarks;
        $leaveApplication->attachment = $attachment;
        $leaveApplication->status = $request->status;
        $leaveApplication->save();
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
}
