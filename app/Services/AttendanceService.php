<?php

namespace App\Services;

use App\Models\AttendanceDetail;
use App\Models\DepartmentWiseShift;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeShift;
use App\Models\LeaveApplication;
use App\Models\MasEmployeeJob;
use App\Models\MasOfficeTiming;
use App\Models\TravelAuthorizationApplication;
use App\Models\WorkHolidayList;
use Carbon\Carbon;

class AttendanceService
{

    public function getAttendanceStatus($empId, $empRegion)
    {
        $currentDate = Carbon::now();
        //checks Mon, Tues, Wed, in full name as this need to be checked off days for shift employee
        $today = Carbon::now()->format('l');
        $attendanceStatus = CREATED_STATUS;
        $isShiftEmp = $this->isShiftEmployee($empId);
        $isEmployeesRequiredOnSaturday = $this->isEmployeesRequiredOnSaturday($empId);
        $weeklyOff = [];

        if($isShiftEmp){
            $weeklyOff = json_decode($isShiftEmp->off_days, true);
        }else if($isEmployeesRequiredOnSaturday){
            $weeklyOff = ['Sunday'];
        }else{
            $weeklyOff = ['Saturday', 'Sunday']; //default as regular employee will have satuarday and sunday off
        }

        $isOnTour = TravelAuthorizationApplication::where('status', '<>', -1)
            ->where('created_by', $empId)
            ->whereHas('details', function ($query) use ($currentDate) {
                $query->whereDate('from_date', '<=', $currentDate->toDateString())
                    ->whereDate('to_date', '>=', $currentDate->toDateString());
            })
            ->with(['details' => function ($query) use ($currentDate) {
                $query->whereDate('from_date', '<=', $currentDate->toDateString())
                    ->whereDate('to_date', '>=', $currentDate->toDateString());
            }])
            ->first();

        if ($isOnTour && $isOnTour->details->isNotEmpty()) {
            return ON_TOUR_STATUS;
        }

        // 2. check if employee is on leave priority over other
        $isOnLeave = LeaveApplication::where('created_by', $empId)
            ->where('status', '<>', -1)
            ->whereDate('from_date', '<=', $currentDate->toDateString())
            ->whereDate('to_date', '>=', $currentDate->toDateString())
            ->first();

        if ($isOnLeave) {
            return $this->prepareLeaveStatus($isOnLeave);
        }

        // 3. Check if it's a holiday first (priority over weekends)
        $matchingHoliday = WorkHolidayList::whereJsonContains('region_id', (string) $empRegion)
            ->whereDate('start_date', '<=', $currentDate->toDateString())
            ->whereDate('end_date', '>=', $currentDate->toDateString())
            ->first();

        if ($matchingHoliday && !$isShiftEmp) {
            return HOLIDAY_STATUS;
        }

        if (is_array($weeklyOff) && in_array($today, $weeklyOff)) {
            return WEEKLY_OFF_STATUS;
        }

        // 5. Check for Saturday  commented as HALF_DAY_WEEKEND_STATUS is applied to those who attend office on Satuarday 
        if ($currentDate->isSaturday() && $isEmployeesRequiredOnSaturday) {
            return HALF_DAY_WEEKEND_STATUS;
        }

        return $attendanceStatus;
    }

    public function getEffectiveOfficeTiming($userData)
    {
        $today = Carbon::now()->format('l');
        $currentMonthNum = getMappedMonth();
        $officeTiming = [];

        $office = $userData->empJob->office;
        $loggedInUserDeptId = $userData->empJob->mas_department_id;
        $officeTiming['office_name'] = $office->name;
        $officeTiming['longitude'] = $office->longitude;
        $officeTiming['latitude'] = $office->latitude;
        $officeTiming['radius'] = $office->radius;
        $officeTiming['attendance_buffer_mins'] = config('global.attendance_buffer_mins');


        // if (isset($userData['employeeInShifts']) && isset($userData['employeeInShifts'][0]['departmentShift'])) {
        //incase if shift is not set then set default mornigng/evening/night time based on checking time
        if (!empty($userData['employeeInShifts'][0])) {
            $shiftData = $userData['employeeInShifts'][0];

            // Decode JSON values into arrays
            $shifts = [
                MORNING_SHIFT => json_decode($shiftData['morning_shift_days'] ?? '[]', true) ?: [], // morning
                EVENING_SHIFT => json_decode($shiftData['evening_shift_days'] ?? '[]', true) ?: [], // evening
                NIGHT_SHIFT => json_decode($shiftData['night_shift_days'] ?? '[]', true) ?: [], // night
                FULL_DAY_SHIFT => json_decode($shiftData['full_shift_days'] ?? '[]', true) ?: [], // full day shift
            ];
        
            // Detect today’s shift type id
            $todayShiftTypeId = collect($shifts)->search(function ($days) use ($today) {
                return in_array($today, $days);
            });
            
            if ($todayShiftTypeId) {
                $departmentWiseShift = DepartmentWiseShift::where('department_id', $loggedInUserDeptId)
                    ->where('type_id', $todayShiftTypeId)
                    ->first();
                
                $officeTiming['start_time'] = $departmentWiseShift->start_time;
                $officeTiming['end_time'] = $departmentWiseShift->end_time;
                $officeTiming['shift_name'] = $departmentWiseShift->shiftType->name;
            }
        } else {
            $isEmployeesRequiredOnSaturday = $this->isEmployeesRequiredOnSaturday(auth()->user()->id);
            $isSpecial = $isEmployeesRequiredOnSaturday ? 1 : 0; //if special follows different timing then rest of the employee

            $defaultOfficeTiming = MasOfficeTiming::where('is_special', $isSpecial)
                ->where(function ($query) use ($currentMonthNum) {
                    $query->whereRaw('? BETWEEN start_month AND end_month', [$currentMonthNum])
                        ->orWhereRaw('start_month > end_month AND (? >= start_month OR ? <= end_month)', [$currentMonthNum, $currentMonthNum]);
                })
                ->select('start_time', 'end_time')
                ->first();

            $officeTiming['start_time'] = $defaultOfficeTiming->start_time;
            $officeTiming['end_time'] = $defaultOfficeTiming->end_time;
            $officeTiming['shift_name'] = 'Regular';
        }

        return $officeTiming;
    }

    public function empAttendanceEntry($loggedInUser, $year = null, $monthYear = null, $flag = null)
    {
        $year = $year;
        $monthYear = $monthYear ?? Carbon::now()->format('m-Y');

        $now = Carbon::now();
        if ($flag === 'yesterday') {
            $now->subDay();
        }
        $currentDay = $now->day;

        $empAttendance = EmployeeAttendance::with(['dailyAttendances' => function ($query) use ($flag, $currentDay) {
            if ($flag === 'daily' || $flag === 'yesterday') {
                $query->where('day', $currentDay);
            }
        }])
            ->year($year)         // optional filter by year
            ->forMonth($monthYear)
            ->first();


        $dailyAttendance = $empAttendance?->dailyAttendances;

        if (!$dailyAttendance || $dailyAttendance->isEmpty()) {
            return null;
        }

        if (!$flag) {
            $attendances = [];
            foreach ($dailyAttendance as $attendance) {
                $detail = AttendanceDetail::where('daily_attendance_id', $attendance->id)
                    ->where('employee_id', $loggedInUser->id)
                    ->first();
                if (!$detail) {
                    $attendances[] = [
                        'check_in_at' => config('global.null_value'),
                        'check_out_at' => config('global.null_value'),
                        'checked_in_from' => config('global.null_value'),
                        'checked_out_from' => config('global.null_value'),
                        'attendance_status_code' => config('global.null_value'),
                        'attendance_status_description' => config('global.null_value'),
                        'status_color' => config('global.null_value'),
                        'worked_hours' => config('global.null_value'),
                        'for_day' => str_pad($attendance->day, 2, '0', STR_PAD_LEFT),
                        'attendance_date' => $attendance->date ?? config('global.null_value'),
                        'remarks' => config('global.null_value'),
                    ];
                    continue; // Skip to the next attendance
                }

                $workedHours = ($detail->check_in_at && $detail->check_out_at)
                    ? Carbon::createFromFormat('H:i:s', $detail->check_in_at)
                    ->diff(Carbon::createFromFormat('H:i:s', $detail->check_out_at))
                    ->format('%Hh:%Im:%Ss')
                    : config('global.null_value');

                $attendances[] = [
                    'check_in_at' => $detail->formatted_check_in_at ?? config('global.null_value'),
                    'check_out_at' => $detail->formatted_check_out_at ?? config('global.null_value'),
                    'checked_in_from' => $detail->check_in_office_id ? ($detail->checkedInFrom?->name ?? config('global.null_value')) : ($detail->check_in_from ?? config('global.null_value')),
                    'checked_out_from' => $detail->check_out_office_id ? ($detail->checkedOutFrom?->name) ?? config('global.null_value') : ($detail->check_out_from ?? config('global.null_value')),
                    'attendance_status_code' => $detail->attendance_status_id == INFORMED_LATE_STATUS ? $detail->present_display_status : $detail->attendanceStatus->code ?? config('global.null_value'),
                    'attendance_status_description' => $detail->attendance_status_id == INFORMED_LATE_STATUS ? $detail->present_status_description : $detail->attendanceStatus->description ?? config('global.null_value'),
                    'status_color' => $detail->attendance_status_id == INFORMED_LATE_STATUS ? $detail->present_status_color : $detail->attendanceStatus->color ?? config('global.null_value'),
                    'worked_hours' => $workedHours,
                    'for_day' => str_pad($attendance->day, 2, '0', STR_PAD_LEFT),
                    'attendance_date' => $detail->created_at->format('d-m-y'),
                    'remarks' => $detail->remarks ?? config('global.null_value')
                ];
            }

            return $attendances;
        }
        // dd($dailyAttendance[0]->id);
        return AttendanceDetail::where('daily_attendance_id', $dailyAttendance[0]->id)
            ->where('employee_id', $loggedInUser->id)
            ->first();
    }

    public function prepareLeaveStatus($isOnLeave)
    {
        switch ($isOnLeave->type_id) {
            case CASUAL_LEAVE:
                return match ($isOnLeave->from_day) {
                    '1' => CASUAL_LEAVE_STATUS,
                    '2' => FHCL_LEAVE_STATUS,
                    '3' => SHCL_LEAVE_STATUS,
                    default => CASUAL_LEAVE_STATUS,
                };
            case EARNED_LEAVE:
                return EARNED_LEAVE_STATUS;
            case MEDICAL_LEAVE:
                return MEDICAL_LEAVE_STATUS;
            case MATERNITY_LEAVE:
                return MATERNITY_LEAVE_STATUS;
            case PATERNITY_LEAVE:
                return PATERNITY_LEAVE_STATUS;
            case BEREAVEMENT_LEAVE:
                return BEREAVEMENT_LEAVE_STATUS;
            case STUDY_LEAVE:
                return STUDY_LEAVE_STATUS;
            case EXTRA_ORDINARY_LEAVE:
                return EOL_LEAVE_STATUS;
        }
    }

    // for shift employee half day will not be allowed
    public function isShiftEmployee($empId){
        $isShiftEmp = EmployeeShift::where('mas_employee_id', $empId)->first();
        return $isShiftEmp;
    }

    // employee that will attend office on Satuarday collected based on office location and if required need to check designation as well especially for RM
    public function isEmployeesRequiredOnSaturday($empId) {
        return MasEmployeeJob::where('mas_employee_id', $empId)
                                ->whereNotIn('mas_office_id', [CC_PLING_OFFICE, CC_THIMPHU_OFFICE, NETOPS_THIMPHU_OFFICE, SAAS_OFFICE, HEAD_OFFICE])
                                ->where('mas_designation_id', '!=', REGIONAL_MANAGER)
                                ->whereNotIn('mas_employee_id', [286, 292]) // at times cases comes where certain employee dont need to come to office despite of their designation and office location
                                ->exists();
    }

}
