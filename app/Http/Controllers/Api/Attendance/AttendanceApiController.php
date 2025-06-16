<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\DailyAttendance;
use App\Models\EmployeeAttendance;
use App\Models\MasAttendanceFeature;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        //
    }

    public function create(){
        $user = auth()->user();
        $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
        $officeTiming = getEffectiveOfficeTiming($user);
        $attendanceEntry = $this->empAttendanceEntry($user);
        if(!$officeTiming){
            return $this->errorResponse('Something went wrong while fetching effective office timing and geo location. Please try again or ask system admin for further information.');
        }

        if(!$attendanceEntry){
            return $this->errorResponse('Attendance entry for date ' . now() . ' has not been created. Please try again or ask system admin for further information.');
        }

        return $this->successResponse([
            'user' => $user,
            'attendance_features' => $attendanceFeatures,
            'office_timings' => $officeTiming,
            'attendance_entry' => $attendanceEntry
        ]);
    }

    public function store(Request $request){
        // $dailyAttendanceId = EmployeeAttendance::with(); //will be current month current date daily attendance id
    }

    public function show($id){
        //
    }

    public function update(Request $request, $id){
        $attendanceDetail = AttendanceDetail::findOrFail($id);
        if(!$attendanceDetail){
            return $this->errorResponse('Something went wrong while making attendance entry. Please try again.');
        }

        AttendanceDetail::where('id', $id)->update([
            'daily_attendance_id' => $request->daily_attendance_id,
            'employee_id' => $request->employee_id,
            'check_in_at' => $request->check_in_at,
            'attendance_status_id' => $request->attendance_status_id,
            'check_out_at' =>$request->check_out_at ?? null,
            'check_in_ip' => null,
            'check_out_ip' => null,
        ]);
        return $this->successResponse('Attendance entry made successfully,');
    }

    public function destroy()
    {
        
    }

}
