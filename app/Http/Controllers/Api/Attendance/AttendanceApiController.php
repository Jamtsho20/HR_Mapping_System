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
        $currentMonth = now()->format('m-Y');
        $attendances = $attendanceService->empAttendanceEntry($user, $year = null, $yearMonth);
        
        if($attendances == null){
            return $this->errorResponse('Attendance for the selected month is not availaible');
        }

        // Filter out future days
        if ($yearMonth === $currentMonth) {
            $currentDay = (int) now()->format('d');
            // Filter out future days
            $attendances = array_filter($attendances, function ($item) use ($currentDay) {
                return (int) $item['for_day'] <= $currentDay;
            });
        }

        // Sort descending by day incase in future client wants to have desc sorting only for cuurnt month then put below code in above if block
        usort($attendances, function ($a, $b) {
            return (int)$b['for_day'] <=> (int)$a['for_day'];
        });

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
        // dd($officeTiming);
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
            $this->rules['check_in_from'] = 'required';
        }else if($request->check_type === 'check-out' && !$request->check_out_at){
            $this->rules['check_out_at'] = 'required';
            $this->rules['check_out_from'] = 'required';
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
        
        // if($device->device_id != $request->device_id){
        //     return $this->errorResponse('Device mismatch detected. Please register this device with the system.');
        // }
        
        // return $this->successResponse($loggedInUserDailyAttendanceEntry);
        if(!$loggedInUserDailyAttendanceEntry){
            return $this->errorResponse('Attendance entry has not been created for ' . Carbon::now()->format('d-m-y') . '. Please ask system admin for further information.');
        }
        
        $attendanceStatus = $loggedInUserDailyAttendanceEntry->attendance_status_id;
        
        $remarks = null;
        //incase of user checks in after 9.05 and 11.00  ( can write a private function to make code lesser in this function) here also need to check for status
        if(!$loggedInUserDailyAttendanceEntry->check_in_at && $loggedInUserDailyAttendanceEntry->attendance_status_id === CREATED_STATUS){
            $officeTiming = $attendanceService->getEffectiveOfficeTiming($user);
            $startTime = Carbon::createFromFormat('H:i:s', $officeTiming['start_time']);
            $bufferedTime = $startTime->copy()->addMinutes($officeTiming['attendance_buffer_mins']);
            $maxEligibleTime = $startTime->copy()->addMinutes(120); 
            $checkInTime = Carbon::parse($request->check_in_at);

            if(($request->check_type == 'check-in' && $request->check_in_at) && $maxEligibleTime->lessThan($checkInTime)){
                $attendanceStatus = ABSENT_STATUS;
                $diff = $checkInTime->diff($startTime);
                $remarks = "Reported late by " . implode(' ', $this->splitTime($diff)) . ", thus marked absent (System generated).";
            }else if(($request->check_type == 'check-in' && $request->check_in_at) && $bufferedTime->lessThan($checkInTime)){
                $attendanceStatus = LATE_STATUS;
                $diff = $checkInTime->diff($bufferedTime);
                $remarks = "Reported late by " . implode(' ', $this->splitTime($diff)) . " (System generated).";
            }else{
                $attendanceStatus = (($request->check_type == 'check-in' && $request->check_in_at) || ($request->check_type == 'check-out' && $request->check_out_at)) ? PRESENT_STATUS : $loggedInUserDailyAttendanceEntry->attendance_status_id;
            }
        }
        
        // Decode existing JSON, or start with an empty array
        $history = $loggedInUserDailyAttendanceEntry->update_history ? json_decode($loggedInUserDailyAttendanceEntry->update_history, true) : [];
        // Append the new entry
        $history[] = [
            'date' => Carbon::now()->toDateTimeString(),
            'attendance_status_id' => $attendanceStatus,
            'remarks' => $remarks,
            'updated_by' => $user->id,
        ];
        
        $updateAttendanceData = [
            'daily_attendance_id' => $loggedInUserDailyAttendanceEntry->daily_attendance_id,
            'employee_id' => $loggedInUserDailyAttendanceEntry->employee_id,
            'attendance_status_id' => $attendanceStatus,
            'remarks' => $remarks,
            'updated_by' => $user->id,
            'update_history' => json_encode($history)
        ];

        // Conditional update based on check type
        if ($request->check_type === 'check-in') {
            $updateAttendanceData['check_in_at'] = $request->check_in_at;
            $updateAttendanceData['check_in_office_id'] = $request->check_in_from;
            $updateAttendanceData['check_in_ip'] = $request->ip();
        } elseif ($request->check_type === 'check-out') {
            $updateAttendanceData['check_out_at'] = $request->check_out_at;
            $updateAttendanceData['check_out_office_id'] = $request->check_out_from;
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

    private function splitTime($diff){
        $parts = [];

        if ($diff->h > 0) {
            $parts[] = "{$diff->h} hour" . ($diff->h > 1 ? 's' : '');
        }
        if ($diff->i > 0) {
            $parts[] = "{$diff->i} minute" . ($diff->i > 1 ? 's' : '');
        }
        if ($diff->s > 0 || empty($parts)) {
            $parts[] = "{$diff->s} second" . ($diff->s > 1 ? 's' : '');
        }
        return $parts;
    }

}
