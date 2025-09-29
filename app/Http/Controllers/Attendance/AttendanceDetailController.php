<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceStatus;
use App\Models\MasDepartment;
use App\Models\MasSection;
use App\Services\DelegationService;
use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AttendanceDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-detail,view')->only('index');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $delegationService = new DelegationService();
        $loggedInUser = User::with(['empJob'])->find(auth()->id());
        // Get user roles using the relationship
        $userRoleIds = $loggedInUser->roles->pluck('id')->toArray();

        $delegatedRoles = $delegationService->delegatedRole($loggedInUser->id);
        $allRoles = collect(array_unique(array_merge($userRoleIds, $delegatedRoles)))->values()->all();
        $attendanceStatus = AttendanceStatus::get();
        //received filter params from form
        $filterDate = $this->getFilterDate($request);
        $employeeFilter = $request->get('employee');
        $deptFilter = $request->get('department');
        $secFilter = $request->get('section');
        $attendanceStatusFilter = $request->get('attendance_status');

        // filter params that need to be displayed in form
        $filterParamsByRole = $this->getFilterParamsByRole($allRoles, $loggedInUser);
        //attendance params is the params that need to be considered before displayinfg attendance data
        $attendanceParamsByRole = $this->getAttendanceParamsByRole($allRoles, $loggedInUser);
        // $attendanceRecords = $this->getAttendanceRecords($employeeIds, $filterDate, $employeeFilter, $attendanceStatusFilter);
        $attendanceRecords = $this->getAttendanceRecords($attendanceParamsByRole, $filterDate, $employeeFilter, $deptFilter, $secFilter, $attendanceStatusFilter);

        $selectedDate = $filterDate->toDateString();

        return view('attendance.attendance-detail.index', compact('privileges', 'filterParamsByRole', 'attendanceRecords', 'selectedDate', 'attendanceStatus'));
    }

    private function getFilterParamsByRole($allRoles, $loggedInUser)
    {
        if (array_intersect([MANAGING_DIRECTOR, ATTENDANCE_MANAGER, ADMIN], $allRoles)) {
            return [
                'employees'   => User::employee()->get(),
                'departments' => MasDepartment::where('status', 1)->get(),
                'sections'    => MasSection::where('status', 1)->get(),
                'attendanceStatus' => AttendanceStatus::get()
            ];
        }

        // Department Head
        if (in_array(DEPARTMENT_HEAD, $allRoles) && $loggedInUser->empJob) {
            $departmentId = $loggedInUser->empJob->mas_department_id;

            return [
                'employees'   => User::employee()
                    ->whereHas('empJob', fn($q) => $q->where('mas_department_id', $departmentId))
                    ->get(),
                'departments' => MasDepartment::where('status', 1)->where('id', $departmentId)->get(),
                'sections'    => MasSection::where('mas_department_id', $departmentId)->get(),
                'attendanceStatus' => AttendanceStatus::get()
            ];
        }

        //immediate Head Role
        if (in_array(IMMEDIATE_HEAD, $allRoles) && $loggedInUser->empJob) {
            $departmentId = $loggedInUser->empJob->mas_department_id;
            $sectionId = $loggedInUser->empJob->mas_section_id;

            return [
                'employees'   => User::employee()
                    ->whereHas('empJob', fn($q) => $q->where('mas_section_id', $sectionId))
                    ->pluck('id')
                    ->toArray(),
                'departments' => [$departmentId],
                'sections'    => [$sectionId],
                'attendanceStatus' => AttendanceStatus::get()
            ];
        }

        // Default: no access
        return ['employees' => [], 'departments' => [], 'sections' => []];
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

    private function getAttendanceParamsByRole(array $allRoles, $loggedInUser)
    {
        // $attendanceStatus = AttendanceStatus::where
        // Higher roles: ADMIN / MD / ATTENDANCE_MANAGER
        if (array_intersect([MANAGING_DIRECTOR, ATTENDANCE_MANAGER, ADMIN], $allRoles)) {
            return [
                'employees'   => User::employee()->pluck('id')->toArray(),
            ];
        }

        // Department Head
        if (in_array(DEPARTMENT_HEAD, $allRoles) && $loggedInUser->empJob) {
            $departmentId = $loggedInUser->empJob->mas_department_id;

            return [
                'employees'   => User::employee()
                    ->whereHas('empJob', fn($q) => $q->where('mas_department_id', $departmentId))
                    ->pluck('id')
                    ->toArray(),
            ];
        }

        //immediate Head Role
        if (in_array(IMMEDIATE_HEAD, $allRoles) && $loggedInUser->empJob) {
            $departmentId = $loggedInUser->empJob->mas_department_id;
            $sectionId = $loggedInUser->empJob->mas_section_id;

            return [
                'employees'   => User::employee()
                    ->whereHas('empJob', fn($q) => $q->where('mas_section_id', $sectionId))
                    ->pluck('id')
                    ->toArray(),
            ];
        }

        // Default: no access
        return ['employees' => []];
    }

    private function getAttendanceRecords($attendanceParamsByRole, Carbon $filterDate, $employeeFilter, $deptFilter, $secFilter, $attendanceStatusFilter)
    {
        $query = \App\Models\AttendanceDetail::with(['employee', 'attendanceStatus'])
            ->whereDate('created_at', $filterDate)
            ->when($attendanceStatusFilter, function ($query) use ($attendanceStatusFilter) {
                $query->where('attendance_status_id', $attendanceStatusFilter);
            })
            ->when($deptFilter, function ($query) use ($deptFilter) {
                $query->where('department_id', $deptFilter);
            })
            ->when($secFilter, function ($query) use ($secFilter) {
                $query->where('section_id', $secFilter);
            })
            ->when($employeeFilter, function ($query) use ($employeeFilter) {
                $query->where('employee_id', $employeeFilter);
            });

        // If employeeIds is empty, it means ATTENDANCE_MANAGER (show all)
        if (!empty($attendanceParamsByRole['employees'])) {
            $query->whereIn('employee_id', $attendanceParamsByRole['employees']);
        }

        return $query->paginate(config('global.pagination'));
    }


    public function exportAttendanceDetail(Request $request)
    {

        $privileges = $request->instance();
        $delegationService = new DelegationService();
        $loggedInUser = User::with(['empJob'])->find(auth()->id());
        // Get user roles using the relationship
        $userRoleIds = $loggedInUser->roles->pluck('id')->toArray();

        $delegatedRoles = $delegationService->delegatedRole($loggedInUser->id);
        $allRoles = collect(array_unique(array_merge($userRoleIds, $delegatedRoles)))->values()->all();
        $attendanceStatus = AttendanceStatus::get();
        //received filter params from form
        $filterDate = $this->getFilterDate($request);
        $employeeFilter = $request->get('employee');
        $deptFilter = $request->get('department');
        $secFilter = $request->get('section');
        $attendanceStatusFilter = $request->get('attendance_status');

        // filter params that need to be displayed in form
        $filterParamsByRole = $this->getFilterParamsByRole($allRoles, $loggedInUser);
        //attendance params is the params that need to be considered before displayinfg attendance data
        $attendanceParamsByRole = $this->getAttendanceParamsByRole($allRoles, $loggedInUser);
        // $attendanceRecords = $this->getAttendanceRecords($employeeIds, $filterDate, $employeeFilter, $attendanceStatusFilter);
        $attendanceRecords = $this->getAttendanceRecords($attendanceParamsByRole, $filterDate, $employeeFilter, $deptFilter, $secFilter, $attendanceStatusFilter);

        $selectedDate = $filterDate->toDateString();


        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.attendance-detail', compact('privileges', 'filterParamsByRole', 'attendanceRecords', 'selectedDate', 'attendanceStatus'))->setPaper('a4', 'landscape');


        // Return the PDF download
        return $pdf->stream('AttendanceDetail-Report.pdf');
    }
}
