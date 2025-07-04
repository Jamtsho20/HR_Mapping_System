<?php

namespace App\Observers;

use App\Models\TravelAuthorizationApplication;
use App\Services\AttendanceService;
use Carbon\Carbon;

class TravelAuthorizationObserver
{
    /**
     * Handle the TravelAuthorizationApplication "created" event.
     */
    public function created(TravelAuthorizationApplication $travelAuthorizationApplication): void
    {
        // dd($travelAuthorizationApplication->details);
        // $attendanceService = new AttendanceService();
        // $currentDate = Carbon::now()->toDateString();
        // if($leaveApplication->from_date <= $currentDate){
        //     $period = CarbonPeriod::create($leaveApplication->from_date, $leaveApplication->to_date);
        //     $attendanceStatus = $attendanceService->prepareLeaveStatus($leaveApplication);
        //     foreach($period as $date){
        //         AttendanceDetail::whereDate('created_at', $date->toDateString())
        //             ->where('employee_id', $leaveApplication->created_by)
        //             ->where(function ($query) {
        //                 $query->where('attendance_status_id', CREATED_STATUS)
        //                     ->orWhere('attendance_status_id', ABSENT_STATUS);
        //             })
        //             ->update([
        //                 'attendance_status_id' => $attendanceStatus,
        //                 'updated_by' => $leaveApplication->created_by
        //             ]);
        //     }
        // }
    }

    /**
     * Handle the TravelAuthorizationApplication "updated" event.
     */
    public function updated(TravelAuthorizationApplication $travelAuthorizationApplication): void
    {
        //
    }

    /**
     * Handle the TravelAuthorizationApplication "deleted" event.
     */
    public function deleted(TravelAuthorizationApplication $travelAuthorization): void
    {
        $travelAuthorization->details()->delete();
    }

    /**
     * Handle the TravelAuthorizationApplication "restored" event.
     */
    public function restored(TravelAuthorizationApplication $travelAuthorizationApplication): void
    {
        //
    }

    /**
     * Handle the TravelAuthorizationApplication "force deleted" event.
     */
    public function forceDeleted(TravelAuthorizationApplication $travelAuthorizationApplication): void
    {
        //
    }
}
