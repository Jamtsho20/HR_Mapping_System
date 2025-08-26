<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\AttendanceStatus;
use App\Models\EmployeeAttendance;
use App\Models\MasDepartment;
use App\Models\MasSection;
use App\Models\User;
use App\Services\DelegationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-summary,view')->only('index');
        // $this->middleware('permission:attendance/attendance-summary,create')->only('store');
        // $this->middleware('permission:attendance/attendance-summary,edit')->only('update');
        // $this->middleware('permission:attendance/attendance-summary,delete')->only('destroy');
    }
    private $attendancesData = [];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $loggedInUser = auth()->user();
        $filterData = $this->prepareFilterData($loggedInUser);
        $departments = $filterData['departments'];
        $sections = $filterData['sections'];
        // $attendanceStatus = AttendanceStatus::get();
        // dd($attendanceStatus);
        $employees = User::whereIsActive(1)
            ->whereNotIn('id', [1, 2])
            ->when($filterData['departmentId'], fn($q) => $q->whereHas('empJob', fn($q) => $q->where('mas_department_id', $filterData['departmentId'])))
            ->when($filterData['sectionId'], fn($q) => $q->whereHas('empJob', fn($q) => $q->where('mas_section_id', $filterData['sectionId'])))
            ->when(
                $filterData['mdRole'],
                fn($q) =>
                $q->whereHas('roles', fn($q) => $q->where('roles.id', DEPARTMENT_HEAD))
            )
            ->get();

        $yearMonth = $request->query('year_month', now()->format('Y-m'));
        $employeeId = $request->query('employee_id');
        // dd($employeeId);
        $departmentId = $request->query('department', $filterData['departmentId'] ?? null);
        $sectionId = $request->query('section', $filterData['sectionId'] ?? null);

        $maxDays = daysInMonth($yearMonth);
        $days = [];

        for ($i = 1; $i <= $maxDays; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        // dd($yearMonth);
        $attendance = EmployeeAttendance::with([
            'dailyAttendances'
        ])
            ->where('for_month', Carbon::parse($yearMonth)->format('m-Y'))
            ->first();
        // dd($attendance->dailyAttendances);
        if ($attendance && $attendance->dailyAttendances->isEmpty()) {
            return back()->with('msg_error', 'Attendance Data for ' . Carbon::parse($yearMonth)->format('F Y') . ' not found.');
        }

        $this->prepareAttendanceData($attendance['dailyAttendances'], $departmentId, $sectionId, $employeeId, $filterData['mdRole']);

        if (empty($this->attendancesData)) {
            return back()->with('msg_error', 'Attendance data for selected parameters not found, Please try againg after correcting the parameters.');
        }


        $perPage = config('global.pagination');
        $page = $request->get('page', 1);
        $collection = collect($this->attendancesData);

        $attendancesData = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // dd($attendancesData);
        return view('attendance.attendance-summary.index', compact('privileges', 'departments', 'sections', 'employees', 'days', 'attendancesData', 'yearMonth', 'departmentId', 'sectionId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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

    private function prepareAttendanceData($dailyAttendance, $departmentId, $sectionId, $employeeId, $mdRole)
    {
        $grouped = [];
        foreach ($dailyAttendance as $attendance) {
            $details = AttendanceDetail::with(['employee', 'attendanceStatus'])
                ->where('daily_attendance_id', $attendance->id)
                ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
                ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->when($mdRole, function ($query) {
                    $query->whereHas('employee.roles', function ($q) {
                        $q->where('role_id', DEPARTMENT_HEAD); // or use role_id: $q->where('id', 3);
                    });
                })
                ->get();
            // dd($details);
            foreach ($details as $detail) {
                $empId = $detail->employee_id;
                $workedHours = ($detail->check_in_at && $detail->check_out_at)
                    ? Carbon::createFromFormat('H:i:s', $detail->check_in_at)
                    ->diff(Carbon::createFromFormat('H:i:s', $detail->check_out_at))
                    ->format('%Hh:%Im:%Ss')
                    : config('global.null_value');
                // Format day as 2-digit string (e.g., "01", "02")
                $forDay = str_pad($attendance->day, 2, '0', STR_PAD_LEFT);

                // Initialize employee block if not exists
                if (!isset($grouped[$empId])) {
                    $grouped[$empId] = [
                        'employee' => $detail->employee->emp_id_name ?? config('global.null_value'),
                        'attendanceMap' => [],
                    ];
                }

                // Fill map for that day
                $grouped[$empId]['attendanceMap'][$forDay] = [
                    'employee' => $detail->employee->emp_id_name ?? config('global.null_value'),
                    'checked_in_from' => $detail->check_in_office_id ? ($detail->checkedInFrom?->name ?? config('global.null_value')) : ($detail->check_in_from ?? config('global.null_value')),
                    'checked_out_from' => $detail->check_out_office_id ? ($detail->checkedOutFrom?->name ?? config('global.null_value')) : ($detail->check_out_from ?? config('global.null_value')),
                    'check_in_at' => $detail->formatted_check_in_at ?? config('global.null_value'),
                    'check_out_at' => $detail->formatted_check_out_at ?? config('global.null_value'),
                    'attendance_status_code' => $detail->attendance_status_id == INFORMED_LATE_STATUS ? $detail->present_display_status : $detail->attendanceStatus->code ?? config('global.null_value'),
                    'attendance_status_description' => $detail->attendance_status_id == INFORMED_LATE_STATUS ? $detail->present_status_description : $detail->attendanceStatus->description ?? config('global.null_value'),
                    'status_color' => $detail->attendance_status_id == INFORMED_LATE_STATUS ? $detail->present_status_color : $detail->attendanceStatus->color ?? config('global.null_value'),
                    'worked_hours' => $workedHours,
                    'for_day' => str_pad($attendance->day, 2, '0', STR_PAD_LEFT),
                    'attendance_date' => getDisplayDateFormat($detail->created_at),
                    'remarks' => $detail->remarks
                ];
            }
        }

        $this->attendancesData = array_values($grouped);
        return $this->attendancesData;
    }

    private function prepareFilterData($loggedInUser)
    {
        $delegationService = new DelegationService();

        $userRoles = $loggedInUser->roles()->pluck('role_id')->toArray();
        $delegatedRoles = $delegationService->delegatedRole($loggedInUser->id);

        $allRoles = collect(array_unique(array_merge($userRoles, $delegatedRoles)));
        $desiredRoles = $allRoles->filter(fn($roleId) => in_array($roleId, [
            ADMIN,
            IMMEDIATE_HEAD,
            DEPARTMENT_HEAD,
            HR,
            HR_MANAGER,
            MANAGING_DIRECTOR
        ]))->values()->all();

        $loggedInUserSec = $loggedInUser->empJob->mas_section_id ?? null;
        $loggedInUserDept = $loggedInUser->empJob->mas_department_id ?? null;

        $deptQuery = MasDepartment::select('id', 'name');
        $secQuery = MasSection::select('id', 'name');

        $sectionId = null;
        $departmentId = null;
        $mdRole = false;

        if (!empty($desiredRoles)) {
            // IMMEDIATE_HEAD (without HR privileges): restrict to section
            if (in_array(IMMEDIATE_HEAD, $desiredRoles) && !in_array(HR, $desiredRoles) && !in_array(HR_MANAGER, $desiredRoles)) {
                $sectionId = $loggedInUserSec;
                $secQuery->where('id', $loggedInUserSec);
                $deptQuery->where('id', $loggedInUserDept); // Show only department of section
            }

            // DEPARTMENT_HEAD (without HR privileges): restrict to department
            if (in_array(DEPARTMENT_HEAD, $desiredRoles) && !in_array(HR, $desiredRoles) && !in_array(HR_MANAGER, $desiredRoles)) {
                $departmentId = $loggedInUserDept;
                $deptQuery->where('id', $loggedInUserDept);
                $secQuery->where('mas_department_id', $loggedInUserDept);
            }

            if (in_array(MANAGING_DIRECTOR, $desiredRoles)) {
                $mdRole = true;
            }

            // HR, HR_MANAGER, MANAGING_DIRECTOR, ADMIN: no filtering (see all)
        }

        $departments = $deptQuery->get();
        $sections = $secQuery->get();

        return compact('departments', 'sections', 'desiredRoles', 'departmentId', 'sectionId', 'mdRole');
    }

    public function exportSamsungDeduction(Request $request)
    {

        $collection = collect($this->attendancesData);



        $privileges = $request->instance();
        $loggedInUser = auth()->user();
        $filterData = $this->prepareFilterData($loggedInUser);
        $departments = $filterData['departments'];
        $sections = $filterData['sections'];
        $employees = User::whereIsActive(1)
            ->whereNotIn('id', [1, 2])
            ->when($filterData['departmentId'], fn($q) => $q->whereHas('empJob', fn($q) => $q->where('mas_department_id', $filterData['departmentId'])))
            ->when($filterData['sectionId'], fn($q) => $q->whereHas('empJob', fn($q) => $q->where('mas_section_id', $filterData['sectionId'])))
            ->when(
                $filterData['mdRole'],
                fn($q) =>
                $q->whereHas('roles', fn($q) => $q->where('roles.id', DEPARTMENT_HEAD))
            )
            ->get();

        $yearMonth = $request->query('year_month', now()->format('Y-m'));
        $employeeId = $request->query('employee_id');
        // dd($employeeId);
        $departmentId = $request->query('department', $filterData['departmentId'] ?? null);
        $sectionId = $request->query('section', $filterData['sectionId'] ?? null);

        $maxDays = daysInMonth($yearMonth);
        $days = [];

        for ($i = 1; $i <= $maxDays; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        // dd($yearMonth);
        $attendance = EmployeeAttendance::with([
            'dailyAttendances'
        ])
            ->where('for_month', Carbon::parse($yearMonth)->format('m-Y'))
            ->first();
        // dd($attendance->dailyAttendances);
        if ($attendance && $attendance->dailyAttendances->isEmpty()) {
            return back()->with('msg_error', 'Attendance Data for ' . Carbon::parse($yearMonth)->format('F Y') . ' not found.');
        }

        $this->prepareAttendanceData($attendance['dailyAttendances'], $departmentId, $sectionId, $employeeId, $filterData['mdRole']);

        if (empty($this->attendancesData)) {
            return back()->with('msg_error', 'Attendance data for selected parameters not found, Please try againg after correcting the parameters.');
        }


        $perPage = config('global.pagination');
        $page = $request->get('page', 1);
        $collection = collect($this->attendancesData);

        $attendancesData = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );



        $yearMonth = $request->query('year_month', now()->format('Y-m'));

        $maxDays = daysInMonth($yearMonth);
        $days = [];

        for ($i = 1; $i <= $maxDays; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }


        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.attendance-summary', compact('attendancesData', 'days'))->setPaper('a4', 'landscape');


        // Return the PDF download
        return $pdf->stream('SamsungDeduction-Report.pdf');
    }

    public function exportSamsungDeductionExcel(Request $request)
    {
        $samsungDeductions = $this->prepareQuery($request)
            ->get();

        return Excel::download(new SamsungDeductionExport($request, $samsungDeductions), 'samsung-deduction-report.xlsx');
    }
}
