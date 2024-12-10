<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasLeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:master/leave-types,view')->only('index');
        $this->middleware('permission:master/leave-types,create')->only('store');
        $this->middleware('permission:master/leave-types,edit')->only('update');
        $this->middleware('permission:master/leave-types,delete')->only('destroy');
    }
    
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $leaveTypes = MasLeaveType::filter($request)->orderBy('name')->paginate(config('global.pagination'));

        return view('masters.leave-types.index', compact('leaveTypes', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('masters.leave-types.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'applicable_to' => 'required'
        ]);

        $leaveTypes = new MasLeaveType();
        $leaveTypes->name = $request->name;
        $leaveTypes->applicable_to = $request->applicable_to;
        $leaveTypes->max_days = $request->max_days;
        $leaveTypes->remarks = $request->remarks;
        $leaveTypes->save();

        return redirect('master/leave-types')->with('msg_success', 'Leave type created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $leaveType = MasLeaveType::findOrFail($id);
        return view('masters.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'applicable_to' => 'required'
        ]);

        $leaveTypes = MasLeaveType::findOrFail($id);
        $leaveTypes->name = $request->name;
        $leaveTypes->applicable_to = $request->applicable_to;
        $leaveTypes->max_days = $request->max_days;
        $leaveTypes->remarks = $request->remarks;
        $leaveTypes->save();

        return redirect('master/leave-types')->with('msg_success', 'leave type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            MasLeaveType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Leave type has been deleted');
        }catch(\Exception $e){
            return back()->with('msg_error', 'Leave type cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
