<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\AttendanceStatus;
use App\Models\MasAttendanceFeature;
use App\Services\AttendanceService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    use JsonResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected $rules = [
        'attendance_status' => 'required',
        'check_in_at' => 'required',
    ];

    public function index(){
        //
    }

    public function create(){
        $user = auth()->user();
        $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
        $attendanceService = new AttendanceService();
        $officeTiming = $attendanceService->getEffectiveOfficeTiming($user) ?? [];
        $attendanceEntry = $attendanceService->empAttendanceEntry($user) ?? [];
        $attendanceStatuses = AttendanceStatus::get(['id', 'code', 'description']);
        // if(!$officeTiming){
        //     return $this->errorResponse('Something went wrong while fetching effective office timing and geo location. Please try again or ask system admin for further information.');
        // }

        // if(!$attendanceEntry){
        //     return $this->errorResponse('Attendance entry for date ' . now() . ' has not been created. Please try again or ask system admin for further information.');
        // }

        return $this->successResponse([
            // 'user' => $user,
            'attendance_features' => $attendanceFeatures,
            'office_timings' => $officeTiming,
            'attendance_entry' => $attendanceEntry,
            'attendance_statuses' => $attendanceStatuses
        ]);
    }

    public function store(Request $request){
        // $dailyAttendanceId = EmployeeAttendance::with(); //will be current month current date daily attendance id
    }

    public function show($id){
        //
    }

    public function update(Request $request, $id){

        $attendanceDetail = AttendanceDetail::find($id);
        $checkInIp = null;
        $checkOutIp = null;
        $validator = \Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        if(!$attendanceDetail){
            return $this->errorResponse('Something went wrong while making attendance entry. Please try again.');
        }
        if($request->check_in_at != '' && !$attendanceDetail->updated_by){
            $checkInIp = $request->ip;
        }
        if($request->check_out_at != ''){
            $checkOutIp = $request->ip;
        }
        AttendanceDetail::where('id', $id)->update([
            'daily_attendance_id' => $request->daily_attendance_id,
            'employee_id' => $request->employee_id,
            'check_in_at' => $request->check_in_at,
            'attendance_status_id' => $request->attendance_status,
            'check_out_at' =>$request->check_out_at ?? null,
            'check_in_ip' => $checkInIp,
            'check_out_ip' => $checkOutIp,
        ]);
        
        return $this->successResponse('Attendance entry made successfully,');
    }

    public function destroy()
    {
        
    }

}
