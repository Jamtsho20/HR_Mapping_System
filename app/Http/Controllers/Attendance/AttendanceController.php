<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasRegion;
use Illuminate\Http\Request;
use App\Services\AttendanceService;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:attendance/my-attendance,view')->only('index');
        $this->middleware('permission:attendance/my-attendance,create')->only('store');
        $this->middleware('permission:attendance/my-attendance,edit')->only('update');
        $this->middleware('permission:attendance/my-attendance,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $attendanceService = new AttendanceService(); 
        $user = auth()->user();
        $year = $request->year ?? Carbon::now()->year; // or allow filtering: $request->get('year', Carbon::now()->year);
        $monthlyAttendances = [];
        $maxDays = 31;

        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $yearMonth = "{$monthStr}-{$year}";

            $startDate = Carbon::createFromFormat('m-Y', $yearMonth)->startOfMonth();
            $daysInMonth = $startDate->daysInMonth;
            
            $attendances = $attendanceService->empAttendanceEntry($user, $year, $yearMonth);

            $attendanceMap = collect($attendances)->keyBy(function ($item) {
                return str_pad($item['for_day'], 2, '0', STR_PAD_LEFT);
            });

            $monthlyAttendances[] = [
                'month' => $startDate->format('F'), // e.g., January
                'attendanceMap' => $attendanceMap,
                'days_in_month' => $daysInMonth
            ];

            $maxDays = max($maxDays, $daysInMonth);
        }

        // Build fixed day headers from 01 to max (typically 31)
        $days = [];
        for ($i = 1; $i <= $maxDays; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        // dd($year);
        return view('attendance.attendance-entry.index', compact('privileges', 'monthlyAttendances', 'days', 'year'));
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
