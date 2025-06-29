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
                AttendanceDetail::whereDate('created_at', $date->toDateString())
                    ->where('employee_id', auth()->user()->id)
                    ->where(function ($query) {
                        $query->where('attendance_status_id', CREATED_STATUS)
                            ->orWhere('attendance_status_id', ABSENT_STATUS);
                    })
                    ->update([
                        'attendance_status_id' => ON_TOUR_STATUS,
                        'updated_by' => auth()->user()->id
                    ]);
            }
        }
    }

    /**
     * Handle the TravelAuthorizationDetails "updated" event.
     */
    public function updated(TravelAuthorizationDetails $travelAuthorizationDetails): void
    {
        $travelAuthorization = TravelAuthorizationApplication::where('travel_authorization_id', $travelAuthorizationDetails->travel_authorization_id)->first();
        $currentDate = Carbon::now()->toDateString();
        if($travelAuthorizationDetails->from_date <= $currentDate){
            $period = CarbonPeriod::create($travelAuthorizationDetails->from_date, $travelAuthorizationDetails->to_date);
            foreach($period as $date){
                AttendanceDetail::whereDate('created_at', $date->toDateString())
                    ->where('employee_id', $travelAuthorization->created_by)
                    ->update([
                        'attendance_status_id' => ABSENT_STATUS,
                        'updated_by' => auth()->user()->id
                    ]);
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
