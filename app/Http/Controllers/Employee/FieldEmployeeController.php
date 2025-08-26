<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FieldEmployee;
use App\Models\User;
use App\Services\DelegationService;
use Illuminate\Http\Request;

class FieldEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee/field-employee,view')->only('index');
        $this->middleware('permission:employee/field-employee,create')->only('store');
        $this->middleware('permission:employee/field-employee,edit')->only('update');
        $this->middleware('permission:employee/field-employee,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $loggedInUser = auth()->user();
        $delegationService = new DelegationService();

        // Get all role IDs including delegated roles
        $userRoleIds = $loggedInUser->roles->pluck('id')->toArray();
        $delegatedRoles = $delegationService->delegatedRole($loggedInUser->id);
        $allRoles = collect(array_unique(array_merge($userRoleIds, $delegatedRoles)))->values()->all();

        // Get employee IDs that the logged-in user can view
        $employeeIds = $this->getEmployeeIdsByRole($allRoles, $loggedInUser->id);

        // Prepare FieldEmployee query
        $fieldEmployeesQuery = FieldEmployee::query()->filter($request);

        // Filter by employee IDs if applicable
        if (!empty($employeeIds)) {
            $fieldEmployeesQuery->whereIn('mas_employee_id', $employeeIds);
        }

        $fieldEmployees = $fieldEmployeesQuery->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'));

        // Prepare employees for filter dropdown
        $employees = $this->getEmployeesForFilter($userRoleIds, $allRoles, $loggedInUser->id);

        return view('employee/field-employee.index', [
            'privileges' => $request->instance(),
            'fieldEmployees' => $fieldEmployees,
            'employees' => $employees,
        ]);
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
            ->where('id', '!=', $loggedInUserId)
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
        return view('employee/field-employee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id',
        ]);

        FieldEmployee::create([
            'mas_employee_id' => $request->mas_employee_id,
        ]);

        return redirect()->route('field-employee.index')
            ->with('msg_success', 'Field Employee created successfully.');
    }

    public function edit(string $id)
    {
        $field = FieldEmployee::findOrFail($id);
        $employees = User::all();
        return view('employee/field-employee.edit', compact('field', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id',
        ]);

        $field = FieldEmployee::findOrFail($id);
        $field->mas_employee_id = $request->mas_employee_id;
        $field->updated_by = auth()->id();
        $field->save();

        return redirect()->route('field-employee.index')
            ->with('msg_success', 'Field Employee updated successfully.');
    }

    public function destroy($id)
    {
        try {
            FieldEmployee::findOrFail($id)->delete();
            return back()->with('msg_success', 'Field Employee has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Field Employee cannot be deleted as it has been used by other module. For further information contact system admin.');
        }
    }
}
