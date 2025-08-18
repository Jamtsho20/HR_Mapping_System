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
        // \Log::info('attendance request:', $request->all());
        $attendanceService = new AttendanceService();
        $user = auth()->user();
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
        // $serverTimeOnly = now()->format('H:i:s'); // e.g. "09:45:30"
        // $device = EmployeeDevices::where('employee_id', $user->id)->first();
        //uncomment it later when fixed
        // $deviceExists = EmployeeDevices::where('employee_id', $user->id)
        //     ->whereRaw('LOWER(device_id) = ?', [strtolower($request->device_id)])
        //     ->exists();

        // if (!$deviceExists) {
        //     return $this->errorResponse('Device mismatch detected or not registered.');
        // }
        
        
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
        DB::transaction(function () use ($loggedInUserDailyAttendanceEntry, $request, $user, $attendanceService) {
            // Lock the attendance row for update
            $attendance = AttendanceDetail::lockForUpdate()->find($loggedInUserDailyAttendanceEntry->id);

            $attendanceStatus = $attendance->attendance_status_id;
            $remarks = $attendance->remarks; // default to existing remarks

            // Calculate attendance status and remarks only if no check-in yet and status is CREATED
            if (!$attendance->check_in_at && $attendance->attendance_status_id === CREATED_STATUS) {
                $officeTiming = $attendanceService->getEffectiveOfficeTiming($user);
                
                $startTime = Carbon::createFromFormat('H:i:s', $officeTiming['start_time']);
                $bufferedTime = $startTime->copy()->addMinutes($officeTiming['attendance_buffer_mins']);
                $maxEligibleTime = $startTime->copy()->addMinutes(120);
                $checkInTime = Carbon::parse($request->check_in_at);

                if ($request->check_type == 'check-in' && $request->check_in_at && $maxEligibleTime->lessThan($checkInTime)) {
                    $attendanceStatus = ABSENT_STATUS;
                    $diff = $checkInTime->diff($startTime);
                    $remarks = "Reported late by " . implode(' ', $this->splitTime($diff)) . ", thus marked absent (System generated).";
                } elseif ($request->check_type == 'check-in' && $request->check_in_at && $bufferedTime->lessThan($checkInTime)) {
                    $attendanceStatus = LATE_STATUS;
                    $diff = $checkInTime->diff($bufferedTime);
                    $remarks = "Reported late by " . implode(' ', $this->splitTime($diff)) . " (System generated).";
                } else {
                    $attendanceStatus = (($request->check_type == 'check-in' && $request->check_in_at) || ($request->check_type == 'check-out' && $request->check_out_at)) ? PRESENT_STATUS : $attendanceStatus;
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
                $attendance->check_in_at = $request->check_in_at;
                $attendance->check_in_office_id = $request->check_in_from;
                $attendance->check_in_ip = $request->ip();
            } elseif ($request->check_type === 'check-out') {
                $attendance->check_out_at = $request->check_out_at;
                $attendance->check_out_office_id = $request->check_out_from;
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
