<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\DepartmentWiseShift;
use App\Models\MasDepartment;
use App\Models\MasShiftType;
use Illuminate\Http\Request;

class DepartmentWiseShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/department-wise-shift,view')->only('index');
        $this->middleware('permission:master/department-wise-shift,create')->only('store');
        $this->middleware('permission:master/department-wise-shift,edit')->only('update');
        $this->middleware('permission:master/department-wise-shift,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $departmentWiseShift = DepartmentWiseShift::filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'));

        return view('masters.department-wise-shift.index', compact('privileges', 'departmentWiseShift'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $departments = MasDepartment::select('id', 'short_name', 'name')->get();
        $types = MasShiftType::select('id', 'name')->get();;

        return view('masters.department-wise-shift.create', compact('departments', 'types'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:mas_departments,id',
            'type_id' => 'required|exists:mas_shift_types,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status.is_active' => 'boolean',
        ]);

        $shift = new \App\Models\DepartmentWiseShift();
        $shift->name = $request->name;
        $shift->department_id = $request->department_id;
        $shift->type_id = $request->type_id;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->status = $request->input('status.is_active', 0);
        $shift->save();

        return redirect('master/department-wise-shift')->with('msg_success', 'Department wise shift created successfully');
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
        $shift = \App\Models\DepartmentWiseShift::findOrFail($id);
        $departments = \App\Models\MasDepartment::all();
        $types = \App\Models\MasShiftType::all();
        return view('masters.department-wise-shift.edit', compact('shift', 'departments', 'types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:mas_departments,id',
            'type_id' => 'required|exists:mas_shift_types,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status.is_active' => 'boolean',
        ]);

        $shift = \App\Models\DepartmentWiseShift::findOrFail($id);
        $shift->name = $request->name;
        $shift->department_id = $request->department_id;
        $shift->type_id = $request->type_id;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->status = $request->input('status.is_active', 0);
        $shift->save();

        return redirect('master/department-wise-shift')->with('msg_success', 'Department wise shift updated successfully');
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
            DepartmentWiseShift::findOrFail($id)->delete();

            return back()->with('msg_success', 'Department Wise Shift has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Department Wise Shift cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
