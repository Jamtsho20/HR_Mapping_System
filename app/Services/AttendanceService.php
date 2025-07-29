<?php

namespace App\Services;

use App\Models\AttendanceDetail;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeShift;
use App\Models\LeaveApplication;
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
        // if employee is in shift then they will have different sets OFF Days based on that OFF Days need to set attendance status.
        $isShiftEmp = EmployeeShift::where('mas_employee_id', $empId)->first();
        
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
            ->whereDate('from_date', '<=', $currentDate->toDateString())
            ->whereDate('to_date', '>=', $currentDate->toDateString())
            ->where('status', '<>', -1)
            ->first();

        if ($isOnLeave) {
            return $this->prepareLeaveStatus($isOnLeave);
        }

        // check if employee is in shift as they will have different sets of off days
        $offDays = $isShiftEmp ? json_decode($isShiftEmp->off_days, true) : [];
        if ($isShiftEmp && is_array($offDays) && in_array($today, $offDays)) {
            return WEEKLY_OFF_STATUS;
        }

        // 3. Check if it's a holiday first (priority over weekends)
        $matchingHoliday = WorkHolidayList::whereJsonContains('region_id', (string) $empRegion)
            ->whereDate('start_date', '<=', $currentDate->toDateString())
            ->whereDate('end_date', '>=', $currentDate->toDateString())
            ->first();
        
        if ($matchingHoliday && !$isShiftEmp) {
            return HOLIDAY_STATUS;
        }

        // 4. Check for Sunday
        if ($currentDate->isSunday() && !$isShiftEmp) {
            return WEEKLY_OFF_STATUS;
        }

        // 5. Check for Saturday  commented as HALF_DAY_WEEKEND_STATUS is not req for now 
        // if ($currentDate->isSaturday() && !$isShiftEmp) {
        //     return HALF_DAY_WEEKEND_STATUS;
        // }

        return $attendanceStatus;
    }

    public function getEffectiveOfficeTiming($userData)
    {
        $currentMonthNum = getMappedMonth();
        $officeTiming = [];
        $office = $userData->empJob->office;
        $officeTiming['office_name'] = $office->name;
        $officeTiming['longitude'] = $office->longitude;
        $officeTiming['latitude'] = $office->latitude;
        // $officeTiming['raidus'] = $office->raidus . ' ' . config('global.raidus_unit');
        $officeTiming['radius'] = $office->radius;
        $officeTiming['attendance_buffer_mins'] = config('global.attendance_buffer_mins');
        if (isset($userData['employeeInShifts']) && isset($userData['employeeInShifts'][0]['departmentShift'])) {
            $officeTiming['start_time'] = $userData['employeeInShifts'][0]['departmentShift']->start_time;
            $officeTiming['end_time'] = $userData['employeeInShifts'][0]['departmentShift']->end_time;
            $officeTiming['shift_name'] = $userData['employeeInShifts'][0]['departmentShift']['shiftType']->name;
        } else {
            $defaultOfficeTiming = MasOfficeTiming::where(function ($query) use ($currentMonthNum) {
                $query->whereRaw('? BETWEEN start_month AND end_month', [$currentMonthNum])
                    ->orWhereRaw('start_month > end_month AND (? >= start_month OR ? <= end_month)', [$currentMonthNum, $currentMonthNum]);
            })->select('start_time', 'end_time')->first();
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
        if($flag === 'yesterday'){
            $now->subDay();
        }
        $currentDay = $now->day;

        $empAttendance = EmployeeAttendance::with(['dailyAttendances' => function ($query) use ($flag, $currentDay) {
                        if($flag === 'daily' || $flag === 'yesterday'){
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

        if(!$flag){
            $attendances = [];
            foreach($dailyAttendance as $attendance){
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
                    'checked_in_from' => $detail->checkedInFrom->name ?? config('global.null_value'),
                    'checked_out_from' => $detail->checkedOutFrom->name ?? config('global.null_value'),
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

    public function prepareLeaveStatus($isOnLeave){
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

}
