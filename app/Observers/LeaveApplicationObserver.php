<?php

namespace App\Observers;

use App\Models\AttendanceDetail;
use App\Models\LeaveApplication;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveApplicationObserver
{
    /**
     * Handle the LeaveApplication "created" event.
     */
    public function created(LeaveApplication $leaveApplication): void
    {
        $leaveApplication->updateLeaveBalance($leaveApplication);
        // 2. Recalculate attendance status for affected leave dates
        $attendanceService = new AttendanceService();
        $currentDate = Carbon::now()->toDateString();
        if($leaveApplication->from_date <= $currentDate){
            $period = CarbonPeriod::create($leaveApplication->from_date, $leaveApplication->to_date);
            $attendanceStatus = $attendanceService->prepareLeaveStatus($leaveApplication);
        
            foreach ($period as $date) {
                $attendanceRecords = AttendanceDetail::whereDate('created_at', $date->toDateString())
                    ->where('employee_id', $leaveApplication->created_by)
                    ->where(function ($query) {
                        $query->where('attendance_status_id', CREATED_STATUS)
                            ->orWhere('attendance_status_id', ABSENT_STATUS)
                            ->orWhere('attendance_status_id', PRESENT_STATUS)
                            ->orWhere('attendance_status_id', LATE_STATUS);
                    })
                    ->get();

                foreach ($attendanceRecords as $attendance) {
                    // Update main fields
                    $attendance->attendance_status_id = $attendanceStatus;
                    $attendance->updated_by = $leaveApplication->created_by;

                    // Handle update history
                    $history = $attendance->update_history ? json_decode($attendance->update_history, true) : [];
                    $history[] = [
                        'date' => now()->toDateTimeString(),
                        'attendance_status_id' => $attendanceStatus,
                        'remarks' => $leaveApplication->remarks,
                        'updated_by' => $leaveApplication->created_by,
                    ];
                    
                    $attendance->update_history = json_encode($history);

                    $attendance->save();
                }
            }
        }
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
            $currentDate = Carbon::now()->toDateString();
            if($leaveApplication->from_date <= $currentDate){
                $period = CarbonPeriod::create($leaveApplication->from_date, $leaveApplication->to_date);
                foreach($period as $date){
                    $attendanceRecords = AttendanceDetail::whereDate('created_at', $date->toDateString())
                        ->where('employee_id', $leaveApplication->created_by)
                        ->get();

                    foreach ($attendanceRecords as $attendance) {
                    // Update main fields
                        $attendance->attendance_status_id = ABSENT_STATUS;
                        $attendance->updated_by = $leaveApplication->updated_by;

                        // Handle update history
                        $history = $attendance->update_history ? json_decode($attendance->update_history, true) : [];

                        $history[] = [
                            'date' => now()->toDateTimeString(),
                            'attendance_status_id' => ABSENT_STATUS,
                            'remarks' => $leaveApplication->remarks,
                            'updated_by' => $leaveApplication->updated_by,
                        ];
                        
                        $attendance->update_history = json_encode($history);

                        $attendance->save();
                    }
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
