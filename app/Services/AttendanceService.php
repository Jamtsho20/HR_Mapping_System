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
        
        // 1. check if employee is on tour
        // $isOnTour = TravelAuthorizationApplication::with(['details' => function ($query) use ($currentDate) {
        //     $query->whereDate('from_date', '<=', $currentDate->toDateString())
        //         ->whereDate('to_date', '>=', $currentDate->toDateString());
        //     }])
        //     ->where('status', '<>', -1)
        //     ->first();

        // if($isOnTour && $isOnTour->details){
        //     dd("a");
        //     return ON_TOUR_STATUS;
        // }
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
            switch ($isOnLeave->type_id) {
                case CASUAL_LEAVE:
                    return match ($isOnLeave->from_day) {
                        1 => CASUAL_LEAVE_STATUS,
                        2 => FHCL_LEAVE_STATUS,
                        3 => SHCL_LEAVE_STATUS,
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

        // 5. Check for Saturday
        if ($currentDate->isSaturday() && !$isShiftEmp) {
            return HALF_DAY_WEEKEND_STATUS;
        }

        return $attendanceStatus;
    }

    public function getEffectiveOfficeTiming($userData)
    {
        $currentMonthNum = getMappedMonth();
        $officeTiming = [];
        $office = $userData->empJob->office;
        $officeTiming['longitude'] = $office->longitude;
        $officeTiming['latitude'] = $office->latitude;
        // $officeTiming['raidus'] = $office->raidus . ' ' . config('global.raidus_unit');
        $officeTiming['raidus'] = $office->raidus;
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

    public function empAttendanceEntry($loggedInUser)
    {
        $currentMonth = Carbon::now()->format('m-Y');
        $currentDay = Carbon::now()->day;
        $departmentId = $loggedInUser->empJob->mas_department_id;
        $sectionId = $loggedInUser->empJob->mas_section_id;

        $empAttendance = EmployeeAttendance::with(['dailyAttendances' => function ($query) use ($departmentId, $sectionId, $currentDay) {
                        $query->where('day', $currentDay);
                        
                        if ($sectionId) {
                            $query->where('section_id', $sectionId);
                        } else {
                            $query->whereNull('section_id')->where('department_id', $departmentId);
                        }
                    }])
                    ->where('for_month', $currentMonth)
                    ->first();

        $dailyAttendance = $empAttendance?->dailyAttendances;

        if (!$dailyAttendance || $dailyAttendance->isEmpty()) {
            return null;
        }

        return AttendanceDetail::where('daily_attendance_id', $dailyAttendance[0]->id)
            ->where('employee_id', $loggedInUser->id)
            ->first();
    }
}
