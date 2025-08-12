<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Services\DelegationService;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AttendanceDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-detail,view')->only('index');
    }

    public function index(Request $request)
    {
        $delegationService = new DelegationService();
        $loggedInUser = auth()->user();

        // Get user roles using the relationship
        $userRoleIds = $loggedInUser->roles->pluck('id')->toArray();

        $delegatedRoles = $delegationService->delegatedRole($loggedInUser->id);
        $allRoles = collect(array_unique(array_merge($userRoleIds, $delegatedRoles)))->values()->all();

        $privileges = $request->instance();
        $filterDate = $this->getFilterDate($request);
        $employeeFilter = $request->get('employee');

        $employeeIds = $this->getEmployeeIdsByRole($userRoleIds, $allRoles, $loggedInUser->id);
        $attendanceRecords = $this->getAttendanceRecords($employeeIds, $filterDate, $employeeFilter);
        $employees = $this->getEmployeesForFilter($userRoleIds, $allRoles, $loggedInUser->id);

        $selectedDate = $filterDate->toDateString();

        return view('attendance.attendance-detail.index', compact('privileges', 'attendanceRecords', 'selectedDate', 'employees'));
    }

    private function getFilterDate(Request $request)
    {
        $filterDate = $request->get('date', Carbon::today()->toDateString());

        try {
            return Carbon::parse($filterDate);
        } catch (\Exception $e) {
            return Carbon::today();
        }
    }

    private function getEmployeeIdsByRole(array $userRoleIds, array $allRoles, int $loggedInUserId)
    {
        if (in_array(DEPARTMENT_HEAD, $userRoleIds)) {
            return $this->getDepartmentHeadEmployees($loggedInUserId);
        }

        if (in_array(IMMEDIATE_HEAD, $userRoleIds)) {
            return $this->getImmediateHeadEmployees($loggedInUserId);
        }

        if (in_array(MANAGING_DIRECTOR, $userRoleIds)) {
            return $this->getManagingDirectorEmployees();
        }

        if (in_array(ATTENDANCE_MANAGER, $allRoles)) {
            return []; // Will fetch all employees
        }

        return [];
    }

    private function getDepartmentHeadEmployees(int $loggedInUserId)
    {
        $loggedInUser = User::with('empJob')->find($loggedInUserId);

        if (!$loggedInUser || !$loggedInUser->empJob || !$loggedInUser->empJob->mas_department_id) {
            return [];
        }

        // Get all users with IMMEDIATE_HEAD role in the same department
        return User::whereHas('empJob', function ($query) use ($loggedInUser) {
                $query->where('mas_department_id', $loggedInUser->empJob->mas_department_id);
            })
            ->pluck('id')
            ->toArray();
    }

    private function getImmediateHeadEmployees(int $loggedInUserId)
    {
        $loggedInUser = User::with('empJob')->find($loggedInUserId);

        if (!$loggedInUser || !$loggedInUser->empJob || !$loggedInUser->empJob->mas_section_id) {
            return [];
        }

        // Get all employees in the same section except the logged-in user
        return User::whereHas('empJob', function ($query) use ($loggedInUser) {
            $query->where('mas_section_id', $loggedInUser->empJob->mas_section_id);
        })
            ->where('id', '!=', $loggedInUserId)
            ->pluck('id')
            ->toArray();
    }

    private function getManagingDirectorEmployees()
    {
        // Get all users with DEPARTMENT_HEAD role
        return User::whereHas('roles', function ($query) {
            $query->where('role_id', DEPARTMENT_HEAD);
        })
            ->pluck('id')
            ->toArray();
    }

    private function getAttendanceRecords(array $employeeIds, Carbon $filterDate, $employeeFilter)
    {
        $query = \App\Models\AttendanceDetail::with(['employee', 'attendanceStatus'])
            ->whereDate('created_at', $filterDate)
            ->when($employeeFilter, function ($query) use ($employeeFilter) {
                $query->where('employee_id', $employeeFilter);
            });

        // If employeeIds is empty, it means ATTENDANCE_MANAGER (show all)
        if (!empty($employeeIds)) {
            $query->whereIn('employee_id', $employeeIds);
        }

        return $query->paginate(config('global.pagination'));
    }

    private function getEmployeesForFilter(array $userRoleIds, array $allRoles, int $loggedInUserId)
    {
        $employeeIds = $this->getEmployeeIdsByRole($userRoleIds, $allRoles, $loggedInUserId);

        // If no specific employee IDs (ATTENDANCE_MANAGER), show all employees
        if (empty($employeeIds) && in_array(ATTENDANCE_MANAGER, $allRoles)) {
            return User::select(['id', 'name', 'employee_id', 'username', 'title'])
                ->active() // Using the scope from your User model
                ->get();
        }

        // If no employee IDs and not ATTENDANCE_MANAGER, return empty collection
        if (empty($employeeIds)) {
            return collect();
        }

        return User::whereIn('id', $employeeIds)
            ->select(['id', 'name', 'employee_id', 'username', 'title'])
            ->active() // Using the scope from your User model
            ->get();
    }

}
