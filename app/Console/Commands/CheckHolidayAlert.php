<?php

namespace App\Console\Commands;

use App\Models\SystemNotification;
use App\Models\WorkHolidayList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckHolidayAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holiday:check-alert';
    protected $description = 'Check if tomorrow is a holiday and store an alert message';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     // Fetch holiday for tomorrow
    //     $holiday = WorkHolidayList::getHolidayForTomorrow();

    //     if ($holiday) {
    //         // If there is a holiday tomorrow, cache the alert message
    //         $alertMessage = "Tomorrow is marked as a holiday: " . $holiday->holiday_name;
    //         Cache::put('holiday_alert_message', $alertMessage, 1440); // Store for 1 day (1440 minutes)
    //         \Log::info('Holiday Alert Message: ' . 'Tomorrow is marked as a holiday: ' . $holiday);
    //     } else {
    //         // Clear any previous alert message from the cache
    //         Cache::forget('holiday_alert_message');
    //     }

    //     $this->info('Holiday check completed!');
    // }
    public function handle()
    {
        // Fetch holiday for tomorrow
        $holiday = WorkHolidayList::getHolidayForTomorrow();  // Assuming this method fetches the holiday for tomorrow

        if ($holiday) {
            // Calculate the number of days between start_date and end_date
            $startDate = Carbon::parse($holiday->start_date); // Convert start_date to Carbon instance
            $endDate = Carbon::parse($holiday->end_date);     // Convert end_date to Carbon instance
            $numberOfDays = $startDate->diffInDays($endDate) + 1;  // Adding 1 because diffInDays does not include the end date

            // Create the alert message with the number of days
            $alertMessage = "Tomorrow is marked as a holiday: " . $holiday->holiday_name . 
                            ". You will get " . $numberOfDays . " day(s) off.";

            // Cache the alert message for 1 day (1440 minutes)
            //Cache::put('holiday_alert_message', $alertMessage, 1440);
            // Insert the notification into the database
            SystemNotification::create([
                'title'      => 'Holiday Alert',
                'message'    => $alertMessage,
                'created_by' => 1, // Set a default user ID, or fetch the system admin ID dynamically
                'updated_by' => null,
            ]);

            
        } else {
            // Clear any previous alert message from the cache
            Cache::forget('holiday_alert_message');
        }

        $this->info('Holiday check completed!');
    }
}
