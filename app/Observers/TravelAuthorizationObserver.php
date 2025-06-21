<?php

namespace App\Observers;

use App\Models\TravelAuthorizationApplication;

class TravelAuthorizationObserver
{
    /**
     * Handle the TravelAuthorizationApplication "created" event.
     */
    public function created(TravelAuthorizationApplication $travelAuthorizationApplication): void
    {
        //
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
