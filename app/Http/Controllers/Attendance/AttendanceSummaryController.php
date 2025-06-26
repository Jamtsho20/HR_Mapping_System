<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\EmployeeAttendance;
use App\Models\MasDepartment;
use App\Models\MasSection;
use App\Models\User;
use App\Services\DelegationService;
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
        $desiredRoles = $this->preparefilterData($loggedInUser);
        $dummySection = 16;
        dd($desiredRoles);
        $yearMonth = $request->query('year_month', now()->format('Y-m'));
        $employeeId = $request->query('employee_id');
        $departmentId = $request->query('department');
        $sectionId = $request->query('section') ?? $dummySection;
        $departments = MasDepartment::select('id', 'name')->get();
        $sections = MasSection::select('id', 'name')->get();
        $employees = User::get();
        $maxDays = daysInMonth($yearMonth);
        $days = [];

        for ($i = 1; $i <= $maxDays; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $attendance = EmployeeAttendance::with([
            'dailyAttendances'
        ])
        ->where('for_month', '=', Carbon::parse($yearMonth)->format('m-Y'))
        ->first();

        if(!$attendance){
            return back()->with('msg_error', 'Attendance Data for ' . Carbon::parse($yearMonth)->format('F Y') . ' not found.');
        }

        $this->prepareAttendanceData($attendance['dailyAttendances'], $departmentId, $sectionId, $employeeId);

        if(empty($this->attendancesData)){
            return back()->with('msg_error', 'Something went wrong while preparing attendance data. Please try again.');
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
        return view('attendance.attendance-summary.index', compact( 'privileges','departments','sections', 'employees', 'days', 'attendancesData', 'yearMonth'));
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

    private function prepareAttendanceData($dailyAttendance, $departmentId, $sectionId, $employeeId){
        $grouped = [];
        foreach($dailyAttendance as $attendance){
            $details = AttendanceDetail::with(['employee', 'attendanceStatus'])
                        ->where('daily_attendance_id', $attendance->id)
                        ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
                        ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
                        ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                        ->get();
           
            foreach($details as $detail){
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
                    'check_in_at' => $detail->check_in_at ?? config('global.null_value'),
                    'check_out_at' => $detail->check_out_at ?? config('global.null_value'),
                    'attendance_status_code' => $detail->attendanceStatus->code ?? config('global.null_value'),
                    'attendance_status_description' => $detail->attendanceStatus->description ?? config('global.null_value'),
                    'status_color' => $detail->attendanceStatus->color ?? config('global.null_value'),
                    'worked_hours' => $workedHours,
                    'for_day' => str_pad($attendance->day, 2, '0', STR_PAD_LEFT),
                    'attendance_date' => $detail->created_at->format('d-m-y'),
                ];
            }
        }

        $this->attendancesData = array_values($grouped);
        return $this->attendancesData;
    }

    private function preparefilterData($loggedInUser){
        $delegationService = new DelegationService();
        $userRoles = $loggedInUser->roles()->pluck('role_id')->toArray();
        $delegatedRole = $delegationService->delegatedRole(auth()->user()->id);
        $allRoles = collect(array_unique(array_merge($userRoles, $delegatedRole)));
        $desiredRoles = $allRoles->filter(function ($roleId) {
            return in_array($roleId, [ADMIN, IMMEDIATE_HEAD, DEPARTMENT_HEAD, HR, HR_MANAGER, MANAGING_DIRECTOR]);
        })->values()->all();
        // dd($desiredRoles);
        return $desiredRoles;
    }
}
