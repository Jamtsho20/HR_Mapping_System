<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\EmployeeDevices;
use App\Models\MasAttendanceFeature;
use App\Models\MasOffice;
use App\Services\AttendanceService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
    use JsonResponseTrait;

    protected $rules = [
        
    ];

    public function index(Request $request){
        $attendanceService = new AttendanceService();
        $user = auth()->user();
        $yearMonth = $request->get('year_month') ?? carbon::now()->format('m-Y');
        $attendances = $attendanceService->empAttendanceEntry($user, $year = null, $yearMonth);
        
        if($attendances == null){
            return $this->errorResponse('Attendance for the selected month is not availaible');
        }

        return $this->successResponse([
            'attendances' => $attendances,
            'year_month' => $yearMonth
        ]);
    }

    public function create(){ 
        $user = auth()->user();
        $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
        $attendanceService = new AttendanceService();
        $offices = MasOffice::whereStatus(1)->get(['id', 'name', 'longitude', 'latitude', 'radius']);
        $officeTiming = $attendanceService->getEffectiveOfficeTiming($user) ?? [];

        return $this->successResponse([
            'attendance_features' => $attendanceFeatures,
            'offices' => $offices,
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
        $device = EmployeeDevices::where('employee_id', $user->id)->first();
        if($request->check_type === 'check-in' && !$request->check_in_at){
            $this->rules['check_in_at'] = 'required';
        }else if($request->check_type === 'check-out' && !$request->check_out_at){
            $this->rules['check_out_at'] = 'required';
        }

        $validator = \Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        
        if($request->attendance_date && $request->shift_name == 'Night Shift'){
            $loggedInUserDailyAttendanceEntry = $attendanceService->empAttendanceEntry($user, $year = null, $monthYear = null, 'yesterday');
        }else{
            $loggedInUserDailyAttendanceEntry = $attendanceService->empAttendanceEntry($user, $year = null, $monthYear = null, 'daily');
        }

        if(!$device){
            return $this->errorResponse('This device is not found. Please register your device with the system to proceed.');
        }
        
        if($device->device_id != $request->device_id){
            return $this->errorResponse('Device mismatch detected. Please register this device with the system.');
        }

        // return $this->successResponse($loggedInUserDailyAttendanceEntry);
        if(!$loggedInUserDailyAttendanceEntry){
            return $this->errorResponse('Attendance entry has not been created for ' . Carbon::now()->format('d-m-y') . '. Please ask system admin for further information.');
        }

        $attendanceStatus = $loggedInUserDailyAttendanceEntry->attendance_status_id;

        // if(($request->check_in_date && $request->check_in_date != Carbon::now()->toDateString()) || ($request->check_out_date && $request->check_out_date != Carbon::now()->toDateString())){
        //     return $this->errorResponse('Please make attendance entry (check-in/check-out) for today`s date i.e, ' . carbon::now()->format('d-m-y') . '.');
        // }

        if($attendanceStatus == CREATED_STATUS){
            $officeTiming = $attendanceService->getEffectiveOfficeTiming($user);
            // if(Carbon::createFromFormat($officeTiming['start_time'] + $officeTiming['attendance_buffer_mins'])->lessThan(Carbon::createFromFormat($request->check_in_at))){
            //     // $attendanceStatus = LATE_STATUS;
            // }else{
            $attendanceStatus = (($request->check_type == 'check-in' && $request->check_in_at) || ($request->check_type == 'check-out' && $request->check_out_at)) ? PRESENT_STATUS : $loggedInUserDailyAttendanceEntry->attendance_status_id;
            // }
        }

        // Decode existing JSON, or start with an empty array
        $history = $loggedInUserDailyAttendanceEntry->update_history ? json_decode($loggedInUserDailyAttendanceEntry->update_history, true) : [];

        // Append the new entry
        $history[] = [
            'date' => Carbon::now()->toDateTimeString(),
            'attendance_status_id' => $attendanceStatus,
            'remarks' => null,
            'updated_by' => $user->id,
        ];

        $updateAttendanceData = [
            'daily_attendance_id' => $loggedInUserDailyAttendanceEntry->daily_attendance_id,
            'employee_id' => $loggedInUserDailyAttendanceEntry->employee_id,
            'attendance_status_id' => $attendanceStatus,
            'updated_by' => $user->id,
            'update_history' => json_encode($history)
        ];

        // Conditional update based on check type
        if ($request->check_type === 'check-in') {
            $updateAttendanceData['check_in_at'] = $request->check_in_at;
            $updateAttendanceData['check_in_ip'] = $request->ip();
        } elseif ($request->check_type === 'check-out') {
            $updateAttendanceData['check_out_at'] = $request->check_out_at;
            $updateAttendanceData['check_out_ip'] = $request->ip();
        }

        AttendanceDetail::where('id', $loggedInUserDailyAttendanceEntry->id)->update($updateAttendanceData);

        return $this->successResponse('Attendance entry for ' . Carbon::now()->format('d-m-y') . ' made successfully');
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy()
    {
        
    }

}
