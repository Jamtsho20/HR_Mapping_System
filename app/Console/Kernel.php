<?php

namespace App\Console;

use App\Models\MasLeaveType;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckHolidayAlert;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        CheckHolidayAlert::class, // Register the custom command
    ];

    protected function schedule(Schedule $schedule): void
    {
        // // $schedule->command('inspire')->hourly();
        // // Get all leave types and policies with their respective plans
        // $leaveTypes = MasLeaveType::with(['leavePolicy.leavePolicyPlan' => function($query) {
        //     $query->where('status', 1)->where('is_information_only', 1);
        // }])->get();
        // // Loop through each leave type and its corresponding leave policy
        // foreach ($leaveTypes as $leaveType) {
        //     // Check if the leave type has an associated leave policy
        //     if ($leaveType->leavePolicy && $leaveType->leavePolicy->leavePolicyPlan) {
        //         $creditFrequency = $leaveType->leavePolicy->leavePolicyPlan->credit_frequency;

        //         // Schedule the command based on the credit frequency
        //         $scheduleCommand = $schedule->command('credit-emp-leave', [$leaveType->id, $leaveType->leavePolicy->leavePolicyPlan->gender]);

        //         // Schedule the command for each leave type based on credit frequency (monthly or yearly)
        //         if ($creditFrequency == 1) {
        //             // Monthly schedule
        //             $scheduleCommand->monthly()->at('00:00');
        //         } else {
        //             // Yearly schedule
        //             $scheduleCommand->yearly()->at('00:00');
        //         }
        //     }
        // }
        $schedule->command('holiday:check-alert')->daily()->at('12:04');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
