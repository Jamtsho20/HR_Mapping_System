<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\AttendanceStatus;
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
        $userRoleIds = DB::table('mas_employee_roles')
            ->where('mas_employee_id', $authEmployeeId)
            ->pluck('role_id')
            ->toArray();

        $privileges = $request->instance();
        $attendanceRecords = collect();

        //  Get filter from query, fallback to today
        $filter = $request->get('day', 'today');
        // $filter = $request->input('day'); // no default, stays null if not selected
        $filterDate = $filter === 'yesterday' ? Carbon::yesterday() : Carbon::today();

        if (in_array(DEPARTMENT_HEAD, $userRoleIds)) {
            $departmentId = DB::table('mas_employee_jobs')
                ->where('mas_employee_id', $authEmployeeId)
                ->value('mas_department_id');

            if ($departmentId) {
                $immediateHeadIds = DB::table('mas_employee_roles as r')
                    ->join('mas_employee_jobs as j', 'j.mas_employee_id', '=', 'r.mas_employee_id')
                    ->where('r.role_id', IMMEDIATE_HEAD)
                    ->where('j.mas_department_id', $departmentId)
                    ->pluck('r.mas_employee_id');

                $attendanceRecords = \App\Models\AttendanceDetail::with(['employee', 'attendanceStatus'])
                    ->whereIn('employee_id', $immediateHeadIds)
                    ->whereDate('created_at', $filterDate)
                    ->get();
            }
        } elseif (in_array(IMMEDIATE_HEAD, $userRoleIds)) {
            $sectionId = DB::table('mas_employee_jobs')
                ->where('mas_employee_id', $authEmployeeId)
                ->value('mas_section_id');

            if ($sectionId) {
                $employeeIds = DB::table('mas_employee_jobs')
                    ->where('mas_section_id', $sectionId)
                    ->where('mas_employee_id', '!=', $authEmployeeId)
                    ->pluck('mas_employee_id');

                $attendanceRecords = \App\Models\AttendanceDetail::with(['employee', 'attendanceStatus'])
                    ->whereIn('employee_id', $employeeIds)
                    ->whereDate('created_at', $filterDate)
                    ->get();
            }
        } elseif (in_array(MANAGING_DIRECTOR, $userRoleIds)) {
            $departmentHeadIds = DB::table('mas_employee_roles')
                ->where('role_id', DEPARTMENT_HEAD)
                ->pluck('mas_employee_id');

            $attendanceRecords = \App\Models\AttendanceDetail::with(['employee', 'attendanceStatus'])
                ->whereIn('employee_id', $departmentHeadIds)
                ->whereDate('created_at', $filterDate)
                ->get();
        }

        return view('attendance.attendance-update.index', compact('privileges', 'attendanceRecords', 'filter'));
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
    // Show the edit form
    public function edit($id)
    {
        $attendanceRecord = AttendanceDetail::with('employee', 'attendanceStatus')->findOrFail($id);

        // Load all possible attendance statuses for a dropdown
        $attendanceStatuses = AttendanceStatus::all();

        return view('attendance.attendance-update.edit', compact('attendanceRecord', 'attendanceStatuses'));
    }

    // Process the form submission
    public function update(Request $request, $id)
    {
        $request->validate([
            'attendance_status_id' => 'required|exists:attendance_statuses,id',
            'remarks' => 'nullable|string|max:500',
        ]);

        $attendanceRecord = AttendanceDetail::findOrFail($id);
        $attendanceRecord->attendance_status_id = $request->attendance_status_id;
        $attendanceRecord->remarks = $request->remarks;
        $attendanceRecord->save();

        return redirect()->route('attendance-update.index')->with('success', 'Attendance updated successfully.');
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
