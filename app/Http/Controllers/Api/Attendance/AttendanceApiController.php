<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
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
        
    ];

    public function index(){dd("a");
        $attendanceService = new AttendanceService();
    }

    public function create(){
        $user = auth()->user();
        $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
        $attendanceService = new AttendanceService();
        $officeTiming = $attendanceService->getEffectiveOfficeTiming($user) ?? [];

        return $this->successResponse([
            'attendance_features' => $attendanceFeatures,
            'office_timings' => $officeTiming,
        ]);
    }

    public function store(Request $request){
        // 
    }

    public function show($id){
        //
    }

    public function attendanceEntry(Request $request){
        $attendanceService = new AttendanceService();
        $user = auth()->user();
        
        if($request->check_in === 'check-in' && !$request->check_in_at){
            $this->rules['check_in_at'] = 'required';
        }
        if($request->check_out === 'check_out' && !$request->check_out_at){
            $this->rules['check_out_at'] = 'required';
        }

        $validator = \Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        $checkInIp = $request->check_in === 'check-in' ? $request->ip() : null;
        $checkOutIp = $request->check_out === 'check-out' ? $request->ip() : null;
        $loggedInUserAttendanceEntry = $attendanceService->empAttendanceEntry($user);

        if(!$loggedInUserAttendanceEntry){
            return $this->errorResponse('Something went wrong while making attendance entry. Please try again.');
        }

        $attendanceStatus = ($request->check_in && $request->check_in_at) ? PRESENT_STATUS : $loggedInUserAttendanceEntry->attendance_status_id;

        AttendanceDetail::where('id', $loggedInUserAttendanceEntry->id)->update([
            'daily_attendance_id' => $loggedInUserAttendanceEntry->daily_attendance_id,
            'employee_id' => $loggedInUserAttendanceEntry->employee_id,
            'check_in_at' => $request->check_in ? $request->check_in_at : $loggedInUserAttendanceEntry->check_in_at,
            'attendance_status_id' => $attendanceStatus,
            'check_out_at' => $request->check_out ? $request->check_out_at : $loggedInUserAttendanceEntry->check_out_at,
            'check_in_ip' => $checkInIp,
            'check_out_ip' => $checkOutIp,
        ]);

        return $this->successResponse('Attendance entry made successfully,');
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy()
    {
        
    }

}
