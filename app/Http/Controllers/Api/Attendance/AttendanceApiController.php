<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
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
        // $user = auth()->user();
        // $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
        // $officeTiming = getEffectiveOfficeTiming($user);
        // if(!$officeTiming){
        //     return $this->errorResponse('Something went wrong while fetching effective office timing and geo location. Please try again.');
        // }
        // return $this->successResponse([
        //     'user' => $user,
        //     'attendance_features' => $attendanceFeatures,
        //     'office_timings' => $officeTiming
        // ]);
    }

    // public function getAttendanceInitialData(){
    //     $user = auth()->user();
    //     $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
    //     $officeTiming = getEffectiveOfficeTiming($user);
    //     if(!$officeTiming){
    //         return $this->errorResponse('Something went wrong while fetching effective office timing and geo location. Please try again.');
    //     }
    //     return $this->successResponse([
    //         'user' => $user,
    //         'attendance_features' => $attendanceFeatures,
    //         'office_timings' => $officeTiming
    //     ]);
    // }

    public function create(){
        $user = auth()->user();
        $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
        $officeTiming = getEffectiveOfficeTiming($user);
        if(!$officeTiming){
            return $this->errorResponse('Something went wrong while fetching effective office timing and geo location. Please try again.');
        }
        return $this->successResponse([
            'user' => $user,
            'attendance_features' => $attendanceFeatures,
            'office_timings' => $officeTiming
        ]);
    }

    public function store(Request $request){
        // $dailyAttendanceId = EmployeeAttendance::with(); //will be current month current date daily attendance id
    }

    public function show($id){

    }

    public function update(Request $request){

    }

    public function destroy()
    {
        
    }
}
