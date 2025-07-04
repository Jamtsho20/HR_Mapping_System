<?php

namespace App\Observers;

use App\Models\TravelAuthorizationApplication;
use App\Models\TravelAuthorizationDetails;
use App\Models\AttendanceDetail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TravelAuthDetailObserver
{
    /**
     * Handle the TravelAuthorizationDetails "created" event.
     */
    public function created(TravelAuthorizationDetails $travelAuthorizationDetails): void
    {
        $currentDate = Carbon::now()->toDateString();
        if($travelAuthorizationDetails->from_date <= $currentDate){
            $period = CarbonPeriod::create($travelAuthorizationDetails->from_date, $travelAuthorizationDetails->to_date);
            foreach($period as $date){
                $attendanceRecords = AttendanceDetail::whereDate('created_at', $date->toDateString())
                    ->where('employee_id', auth()->user()->id)
                    ->where(function ($query) {
                        $query->where('attendance_status_id', CREATED_STATUS)
                            ->orWhere('attendance_status_id', ABSENT_STATUS);
                    })->get();

                foreach ($attendanceRecords as $attendance) {
                    // Update main fields
                    $attendance->attendance_status_id = ON_TOUR_STATUS;
                    $attendance->updated_by = auth()->user()->id;

                    // Handle update history
                    $history = $attendance->update_history ? json_decode($attendance->update_history, true) : [];

                    $history[] = [
                        'date' => now()->toDateTimeString(),
                        'attendance_status_id' => ABSENT_STATUS,
                        'remarks' => $travelAuthorizationDetails->purpose,
                        'updated_by' => auth()->user()->id,
                    ];
                    
                    $attendance->update_history = json_encode($history);

                    $attendance->save();
                }
            }
        }
    }

    /**
     * Handle the TravelAuthorizationDetails "updated" event.
     */
    public function updated(TravelAuthorizationDetails $travelAuthorizationDetails): void
    {
        $travelAuthorization = TravelAuthorizationApplication::with(['audit_logs' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->where('travel_authorization_id', $travelAuthorizationDetails->travel_authorization_id)->first();
        $latestLog = $travelAuthorization->audit_logs->first(); //get latest audit logs dso that remarks can be used in attendance

        $currentDate = Carbon::now()->toDateString();
        if($travelAuthorizationDetails->from_date <= $currentDate){
            $period = CarbonPeriod::create($travelAuthorizationDetails->from_date, $travelAuthorizationDetails->to_date);
            foreach($period as $date){
                $attendanceRecords = AttendanceDetail::whereDate('created_at', $date->toDateString())
                    ->where('employee_id', $travelAuthorization->created_by)
                    ->get();

                foreach ($attendanceRecords as $attendance) {
                    // Update main fields
                    $attendance->attendance_status_id = ABSENT_STATUS;
                    $attendance->updated_by = auth()->user()->id;

                    // Handle update history
                    $history = $attendance->update_history ? json_decode($attendance->update_history, true) : [];

                    $history[] = [
                        'date' => now()->toDateTimeString(),
                        'attendance_status_id' => ABSENT_STATUS,
                        'remarks' => $latestLog->remarks,
                        'updated_by' => auth()->user()->id,
                    ];
                    
                    $attendance->update_history = json_encode($history);

                    $attendance->save();
                }
            }
        }
    }

    /**
     * Handle the TravelAuthorizationDetails "deleted" event.
     */
    public function deleted(TravelAuthorizationDetails $travelAuthorizationDetails): void
    {
        //
    }

    /**
     * Handle the TravelAuthorizationDetails "restored" event.
     */
    public function restored(TravelAuthorizationDetails $travelAuthorizationDetails): void
    {
        //
    }

    /**
     * Handle the TravelAuthorizationDetails "force deleted" event.
     */
    public function forceDeleted(TravelAuthorizationDetails $travelAuthorizationDetails): void
    {
        //
    }
}
