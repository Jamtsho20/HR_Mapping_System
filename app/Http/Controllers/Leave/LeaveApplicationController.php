<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
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
        'from_date' => 'required|date',
        'to_date' => 'required|date',
        'no_of_days' => 'required'
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
        $leaveApplication = new LeaveApplication();
        $attachment = "";
        if ($request->attachment) {
            $file = $request->attachment;
            $attachment = uploadImageToDirectory($file, 'images/leave/');
        }
        $leaveApplication->mas_employee_id = $request->mas_employee_id;
        $leaveApplication->mas_leave_type_id = $request->mas_leave_type_id;
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
        $leaveApplication = LeaveApplication::findOrfail($id);
        // return
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
}
