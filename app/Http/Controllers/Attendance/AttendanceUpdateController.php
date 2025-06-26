<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-update,view')->only('index');
        $this->middleware('permission:attendance/attendance-update,create')->only('store');
        $this->middleware('permission:attendance/attendance-update,edit')->only('update');
        $this->middleware('permission:attendance/attendance-update,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $authEmployeeId = auth()->user()->id;
        $userRoleId = DB::table('mas_employee_roles')
            ->where('mas_employee_id', $authEmployeeId)
            ->pluck('role_id')
            ->toArray();
        $privileges = $request->instance();
        $attendanceRecords = collect();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();


        if (in_array(DEPARTMENT_HEAD, $userRoleId)) {
            // Department Head → Immediate Heads in same department
            $departmentId = DB::table('mas_employee_jobs')
                ->where('mas_employee_id', $authEmployeeId)
                ->value('mas_department_id');

            if ($departmentId) {
                $immediateHeadIds = DB::table('mas_employee_roles as r')
                    ->join('mas_employee_jobs as j', 'j.mas_employee_id', '=', 'r.mas_employee_id')
                    ->where('r.role_id', IMMEDIATE_HEAD)
                    ->where('j.mas_department_id', $departmentId)
                    ->pluck('r.mas_employee_id');

                $attendanceRecords = DB::table('attendance_details')
                    ->whereIn('employee_id', $immediateHeadIds)
                    ->where(function ($q) use ($today, $yesterday) {
                        $q->whereDate('created_at', $today)
                            ->orWhereDate('created_at', $yesterday);
                    })
                    ->get();
            }
        } elseif (in_array(IMMEDIATE_HEAD, $userRoleId)) {
            $sectionId = DB::table('mas_employee_jobs')
                ->where('mas_employee_id', $authEmployeeId)
                ->value('mas_section_id');

            if ($sectionId) {
                $employeeIds = DB::table('mas_employee_jobs')
                    ->where('mas_section_id', $sectionId)
                    ->where('mas_employee_id', '!=', $authEmployeeId) // 🔥 exclude self
                    ->pluck('mas_employee_id');

                $attendanceRecords = DB::table('attendance_details')
                    ->whereIn('employee_id', $employeeIds)
                    ->where(function ($q) use ($today, $yesterday) {
                        $q->whereDate('created_at', $today)
                            ->orWhereDate('created_at', $yesterday);
                    })
                    ->get();
            }
        } elseif (in_array(MANAGING_DIRECTOR, $userRoleId)) {
            // Managing Director → All Department Heads
            $departmentHeadIds = DB::table('mas_employee_roles')
                ->where('role_id', DEPARTMENT_HEAD)
                ->pluck('mas_employee_id');

            $attendanceRecords = DB::table('attendance_details')
                ->whereIn('employee_id', $departmentHeadIds)
                ->where(function ($q) use ($today, $yesterday) {
                    $q->whereDate('created_at', $today)
                        ->orWhereDate('created_at', $yesterday);
                })
                ->get();
        }
        return view('attendance.attendance-update.index', compact('privileges', 'attendanceRecords'));
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
}
