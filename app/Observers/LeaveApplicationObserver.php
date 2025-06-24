<?php

namespace App\Observers;

use App\Models\AttendanceDetail;
use App\Models\LeaveApplication;
use App\Services\AttendanceService;
use Carbon\Carbon;


class LeaveApplicationObserver
{
    /**
     * Handle the LeaveApplication "created" event.
     */
    public function created(LeaveApplication $leaveApplication): void
    {
        $leaveApplication->updateLeaveBalance($leaveApplication);
    }

    /**
     * Handle the LeaveApplication "updated" event.
     */
 public function updated(LeaveApplication $leaveApplication): void
    {
        if ($leaveApplication->isDirty('status') && $leaveApplication->status == -1) {

            // 1. Revert leave balance
            $leaveApplication->updateLeaveBalance($leaveApplication);

            // 2. Recalculate attendance status for affected leave dates
            $employeeId = $leaveApplication->created_by;
            $leaveDates = Carbon::parse($leaveApplication->from_date)->daysUntil($leaveApplication->to_date);

            $attendanceService = new AttendanceService();

            foreach ($leaveDates as $date) {
                $dateString = Carbon::parse($date)->toDateString();

                $attendanceDetail = AttendanceDetail::where('employee_id', $employeeId)
                    ->whereDate('created_at', $dateString)
                    ->first();

                if (!$attendanceDetail) {
                    continue;
                }

                $employee = $attendanceDetail->employee;

                // Fetch region id through job and office
                $regionId = optional(optional($employee?->empJob)?->office)->mas_region_id;

                if (!$regionId) {
                    continue;
                }

                // Get new attendance status for this date and region
                $newStatus = $attendanceService->getAttendanceStatus($employeeId, $regionId, Carbon::parse($date));


                if ($attendanceDetail->attendance_status_id != $newStatus) {
                    $attendanceDetail->attendance_status_id = $newStatus;
                    $attendanceDetail->save();
                } else {
                }
            }
        }
    }
    /**
     * Handle the LeaveApplication "deleted" event.
     */
    public function deleted(LeaveApplication $leaveApplication): void
    {
        //
    }

    /**
     * Handle the LeaveApplication "restored" event.
     */
    public function restored(LeaveApplication $leaveApplication): void
    {
        //
    }

    /**
     * Handle the LeaveApplication "force deleted" event.
     */
    public function forceDeleted(LeaveApplication $leaveApplication): void
    {
        //
    }
}
