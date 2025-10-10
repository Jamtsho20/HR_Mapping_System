<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeShift;
use App\Models\User;
use App\Services\DelegationService;
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
    $loggedInUser = auth()->user();
    $delegationService = new DelegationService();

    $userRoleIds = $loggedInUser->roles->pluck('id')->toArray();
    $delegatedRoles = $delegationService->delegatedRole($loggedInUser->id);
    $allRoles = collect(array_unique(array_merge($userRoleIds, $delegatedRoles)))->values()->all();

    $employeeIds = $this->getEmployeeIdsByRole($allRoles, $loggedInUser->id);

    // Query EmployeeShift with filters
    $employeeShiftsQuery = EmployeeShift::query()->filter($request);

    if (!empty($employeeIds)) {
        $employeeShiftsQuery->whereIn('mas_employee_id', $employeeIds);
    }

    $employeeShifts = $employeeShiftsQuery->orderBy('created_at', 'desc')
        ->paginate(config('global.pagination'));
    
    // Prepare employees for filter dropdown
    $employees = $this->getEmployeesForFilter($userRoleIds, $allRoles, $loggedInUser->id);
    
    $privileges = $request->instance();

    return view('employee/shift-employee.index', compact('privileges', 'employeeShifts', 'employees'));
}

    private function getEmployeeIdsByRole(array $allRoles, int $loggedInUserId)
    {
        if (in_array(DEPARTMENT_HEAD, $allRoles)) {
            return $this->getDepartmentHeadEmployees($loggedInUserId);
        }
        if (in_array(IMMEDIATE_HEAD, $allRoles)) {
            return $this->getImmediateHeadEmployees($loggedInUserId);
        }
        if (in_array(SUPERVISOR, $allRoles)) {
            return $this->getSupervisorEmployees($loggedInUserId);
        }
        if (in_array(MANAGING_DIRECTOR, $allRoles)) {
            return $this->getManagingDirectorEmployees();
        }
        if (in_array(ATTENDANCE_MANAGER, $allRoles) || in_array(ADMIN, $allRoles)) {
            return []; // Will fetch all employees
        }
        return [];
    }

    private function getDepartmentHeadEmployees(int $loggedInUserId)
    {
        $user = User::with('empJob')->find($loggedInUserId);

        if (!$user || !$user->empJob || !$user->empJob->mas_department_id) {
            return [];
        }

        return User::whereHas('roles', fn($q) => $q->where('role_id', IMMEDIATE_HEAD))
            ->whereHas('empJob', fn($q) => $q->where('mas_department_id', $user->empJob->mas_department_id))
            ->pluck('id')
            ->toArray();
    }

    private function getImmediateHeadEmployees(int $loggedInUserId)
    {
        $user = User::with('empJob')->find($loggedInUserId);

        if (!$user || !$user->empJob || !$user->empJob->mas_section_id) {
            return [];
        }

        return User::whereHas('empJob', fn($q) => $q->where('mas_section_id', $user->empJob->mas_section_id))
            ->where('id', '!=', $loggedInUserId)
            ->pluck('id')
            ->toArray();
    }

    private function getSupervisorEmployees(int $loggedInUserId)
    {
        $user = User::with('empJob')->find($loggedInUserId);

        if (!$user || !$user->empJob || !$user->empJob->mas_section_id) {
            return [];
        }

        return User::whereHas('empJob', fn($q) => $q->where('mas_section_id', $user->empJob->mas_section_id))
            // ->where('id', $loggedInUserId)
            ->pluck('id')
            ->toArray();
    }

    private function getManagingDirectorEmployees()
    {
        return User::whereHas('roles', fn($q) => $q->where('role_id', DEPARTMENT_HEAD))
            ->pluck('id')
            ->toArray();
    }

    private function getEmployeesForFilter(array $userRoleIds, array $allRoles, int $loggedInUserId)
    {
        $employeeIds = $this->getEmployeeIdsByRole($allRoles, $loggedInUserId);

        if (empty($employeeIds) && (in_array(ATTENDANCE_MANAGER, $allRoles) || in_array(ADMIN, $allRoles))) {
            return User::select(['id', 'name', 'employee_id', 'username', 'title'])
                ->whereNotIn('id', [SUPER_USER_ID, SAP_USER_ID])
                ->active()
                ->get();
        }

        if (empty($employeeIds)) {
            return collect();
        }

        return User::whereIn('id', $employeeIds)
            ->select(['id', 'name', 'employee_id', 'username', 'title'])
            ->active()
            ->get();
    }


    public function create()
    {
        $shifts = \App\Models\DepartmentWiseShift::all();
        return view('employee/shift-employee/create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id|unique:employee_shifts,mas_employee_id',
            // 'department_shift_id' => 'required|exists:department_wise_shifts,id',
            // 'off_days' => 'required|array|min:1',
            // 'off_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ],
        [
            'mas_employee_id.unique' => 'This employee already has a shift assigned.',
            'mas_employee_id.exists' => 'The selected employee does not exist.',
            'mas_employee_id.required' => 'Please select an employee.',
    ]);

        $shift = new \App\Models\EmployeeShift();
        $shift->mas_employee_id = $request->mas_employee_id;
        // $shift->department_shift_id = $request->department_shift_id;
        $shift->morning_shift_days = json_encode($request->morning_shift_days);
        $shift->evening_shift_days = json_encode($request->evening_shift_days);
        $shift->night_shift_days = json_encode($request->night_shift_days);
        $shift->full_shift_days = json_encode($request->full_shift_days);
        $shift->off_days = json_encode($request->off_days);
        $shift->save();

        return redirect()->route('shift-employee.index')
            ->with('msg_success', 'Employee shift assigned successfully.');
    }

    public function edit(string $id)
    {
        $shift = \App\Models\EmployeeShift::findOrFail($id);
        $employees = User::all();
        // $shifts = DepartmentWiseShift::all();
        return view('employee/shift-employee.edit', compact('shift', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id',
            // 'department_shift_id' => 'required|exists:department_wise_shifts,id',
            // 'off_days' => 'required|array|min:1',
            // 'off_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ],
        [
                // 'mas_employee_id.unique' => 'This employee already has a shift assigned.',
                'mas_employee_id.exists' => 'The selected employee does not exist.',
                'mas_employee_id.required' => 'Please select an employee.',
        ]);

        $shift = EmployeeShift::findOrFail($id);
        $shift->mas_employee_id = $request->mas_employee_id;
        // $shift->department_shift_id = $request->department_shift_id;
        $shift->morning_shift_days = json_encode($request->morning_shift_days);
        $shift->evening_shift_days = json_encode($request->evening_shift_days);
        $shift->night_shift_days = json_encode($request->night_shift_days);
        $shift->full_shift_days = json_encode($request->full_shift_days);
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
