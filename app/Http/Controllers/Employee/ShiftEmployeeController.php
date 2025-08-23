<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DepartmentWiseShift;
use App\Models\EmployeeShift;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee/shift-employee,view')->only('index');
        $this->middleware('permission:employee/shift-employee,create')->only('store');
        $this->middleware('permission:employee/shift-employee,edit')->only('update');
        $this->middleware('permission:employee/shift-employee,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employees = User::filter($request)->select(['id', 'name', 'employee_id', 'username', 'title'])->get();
        $employeeShifts = EmployeeShift::filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'));

        return view('employee/shift-employee.index', compact('privileges', 'employeeShifts', 'employees'));
    }

    public function create()
    {
        $shifts = \App\Models\DepartmentWiseShift::all();
        return view('employee/shift-employee/create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id',
            'department_shift_id' => 'required|exists:department_wise_shifts,id',
            'off_days' => 'required|array|min:1',
            'off_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ]);

        $shift = new \App\Models\EmployeeShift();
        $shift->mas_employee_id = $request->mas_employee_id;
        $shift->department_shift_id = $request->department_shift_id;
        $shift->off_days = json_encode($request->off_days);
        $shift->save();

        return redirect()->route('shift-employee.index')
            ->with('msg_success', 'Employee shift assigned successfully.');
    }

    public function edit(string $id)
    {
        $shift = \App\Models\EmployeeShift::findOrFail($id);
        $employees = User::all();
        $shifts = DepartmentWiseShift::all();
        return view('employee/shift-employee.edit', compact('shift', 'employees', 'shifts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id',
            'department_shift_id' => 'required|exists:department_wise_shifts,id',
            'off_days' => 'required|array|min:1',
            'off_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ]);

        $shift = EmployeeShift::findOrFail($id);
        $shift->mas_employee_id = $request->mas_employee_id;
        $shift->department_shift_id = $request->department_shift_id;
        $shift->off_days = $request->off_days; // if $casts exists
        $shift->updated_by = auth()->id();
        $shift->save();

        return redirect()->route('shift-employee.index')
            ->with('msg_success', 'Employee shift updated successfully.');
    }

    public function destroy($id)
    {
        try {
            EmployeeShift::findOrFail($id)->delete();

            return back()->with('msg_success', 'Attendance Feature has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Attendance Feature cannot be deleted as it has been used by other module. For further information contact system admin.');
        }
    }
}
