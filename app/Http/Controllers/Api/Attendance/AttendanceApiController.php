<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\EmployeeDevices;
// use App\Models\EmployeeDevices;
use App\Models\FieldEmployee;
use App\Models\MasAttendanceFeature;
use App\Models\MasOffice;
use App\Models\User;
use App\Services\AttendanceService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        // $isFieldEmp = FieldEmployee::where('mas_employee_id', $user->id)->exists();
        
        return $this->successResponse([
            'attendance_features' => $attendanceFeatures,
            'offices' => $offices,
            'office_timings' => $officeTiming
            // 'is_field_emp' => $isFieldEmp
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
        
        $isFieldEmp = FieldEmployee::where('mas_employee_id', $user->id)->exists();
        $type = $request->check_type;
        $isCheckIn  = $type === 'check-in';
        $isCheckOut = $type === 'check-out';
        $serverTime = now()->subMinutes(4)->format('H:i:s'); //Default Server time set in app config file follows Asia/Thimphu time Zone

        if ($isCheckIn) {
            // $this->rules['check_in_at'] = 'required';
            if ($isFieldEmp) {
                $this->rules['check_in_from_location'] = 'required';
            } else {
                $this->rules['check_in_from'] = 'required';
            }
        }

        if ($isCheckOut) {
            // $this->rules['check_out_at'] = 'required';
            if ($isFieldEmp) {
                $this->rules['check_out_from_location'] = 'required';
            } else {
                $this->rules['check_out_from'] = 'required';
            }
        }

        $validator = \Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        // $serverTimeOnly = now()->format('H:i:s'); // e.g. "09:45:30"
        //uncomment it later when fixed
        $deviceExists = EmployeeDevices::where('employee_id', $user->id)
            ->whereRaw('LOWER(device_id) = ?', [strtolower($request->device_id)])
            ->exists();

        if (!$deviceExists) {
            return $this->errorResponse('Device mismatch detected or not registered.');
        }
        
        if($request->attendance_date && $request->shift_name == 'Night Shift'){
            $loggedInUserDailyAttendanceEntry = $attendanceService->empAttendanceEntry($user, $year = null, $monthYear = null, 'yesterday');
        }else{
            $loggedInUserDailyAttendanceEntry = $attendanceService->empAttendanceEntry($user, $year = null, $monthYear = null, 'daily');
        }
        
        // return $this->successResponse($loggedInUserDailyAttendanceEntry);
        if(!$loggedInUserDailyAttendanceEntry){
            return $this->errorResponse('Attendance entry has not been created for ' . Carbon::now()->format('d-m-y') . '. Please ask system admin for further information.');
        }

        //new code here
        // Use transaction with row locking to prevent race conditions
        DB::transaction(function () use ($loggedInUserDailyAttendanceEntry, $request, $user, $attendanceService, $isFieldEmp, $serverTime) {
            // Lock the attendance row for update
            $attendance = AttendanceDetail::lockForUpdate()->find($loggedInUserDailyAttendanceEntry->id);

            $attendanceStatus = $attendance->attendance_status_id;
            $remarks = $attendance->remarks; // default to existing remarks

            // Calculate attendance status and remarks only if no check-in yet and status is CREATED
            if (!$attendance->check_in_at && ($attendance->attendance_status_id === CREATED_STATUS || $attendance->attendance_status_id === INFORMED_LATE_STATUS)) {
                $officeTiming = $attendanceService->getEffectiveOfficeTiming($user);
                $startTime = Carbon::createFromFormat('H:i:s', $officeTiming['start_time']);
                $bufferedTime = $startTime->copy()->addMinutes($officeTiming['attendance_buffer_mins']);
                $maxEligibleTime = $startTime->copy()->addMinutes(120);
                // $checkInTime = Carbon::parse($request->check_in_at);
                $checkInTime = Carbon::parse($serverTime);

                // if ($request->check_type == 'check-in' && $request->check_in_at && $maxEligibleTime->lessThan($checkInTime)) {
                if ($request->check_type == 'check-in' && $checkInTime && $maxEligibleTime->lessThan($checkInTime)) {
                    $attendanceStatus = ABSENT_STATUS;
                    $diff = $checkInTime->diff($startTime);
                    $remarks = "Reported late by " . implode(' ', $this->splitTime($diff)) . ", thus marked absent (System generated).";
                // } elseif ($request->check_type == 'check-in' && $request->check_in_at  && $bufferedTime->lessThan($checkInTime)) {
                } elseif ($request->check_type == 'check-in' && $checkInTime && $bufferedTime->lessThan($checkInTime)) {
                    $attendanceStatus = $attendance->attendance_status_id != INFORMED_LATE_STATUS ? LATE_STATUS : INFORMED_LATE_STATUS;
                    $diff = $checkInTime->diff($bufferedTime);
                    $remarks = $attendance->attendance_status_id != INFORMED_LATE_STATUS ? "Reported late by " . implode(' ', $this->splitTime($diff)) . " (System generated)." : ($attendance->remarks ?? 'Marked as Informed late by supervisor (System generated as remarks not provided).');
                } else {
                    // $attendanceStatus = (($request->check_type == 'check-in' && $request->check_in_at) || ($request->check_type == 'check-out' && $request->check_out_at)) ? PRESENT_STATUS : $attendanceStatus;
                    $attendanceStatus = ($request->check_type == 'check-in' || $request->check_type == 'check-out') ? PRESENT_STATUS : $attendanceStatus;
                }
            }

            // Update history JSON safely
            $history = $attendance->update_history ? json_decode($attendance->update_history, true) : [];
            $history[] = [
                'date' => now()->toDateTimeString(),
                'attendance_status_id' => $attendanceStatus,
                'remarks' => $remarks,
                'updated_by' => $user->id,
            ];

            // Update fields
            $attendance->attendance_status_id = $attendanceStatus;
            $attendance->remarks = $remarks;
            $attendance->updated_by = $user->id;
            $attendance->update_history = json_encode($history);

            // Update check-in/out data conditionally
            if ($request->check_type === 'check-in') {
                // $attendance->check_in_at = $request->check_in_at;
                $attendance->check_in_at = $serverTime;
                $attendance->check_in_office_id = !$isFieldEmp ? $request->check_in_from : null;
                $attendance->check_in_from = $isFieldEmp ? $request->check_in_from_location : null;
                $attendance->check_in_coordinates = $isFieldEmp ? $request->check_in_coordinates: null;
                $attendance->check_in_ip = $request->ip();
            } elseif ($request->check_type === 'check-out') {
                // $attendance->check_out_at = $request->check_out_at;
                $attendance->check_out_at = $serverTime;
                $attendance->check_out_office_id = !$isFieldEmp ? $request->check_out_from : null;
                $attendance->check_out_from = $isFieldEmp ? $request->check_out_from_location : null;
                $attendance->check_out_coordinates = $isFieldEmp ? $request->check_out_coordinates: null;
                $attendance->check_out_ip = $request->ip();
            }

            $attendance->save();
        });
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
