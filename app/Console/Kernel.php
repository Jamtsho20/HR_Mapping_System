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
        // $schedule->command('credit-emp-earned-leaves-monthly')->monthly()->at('00:00');
        // $schedule->command('credit-emp-leaves-yearly')->yearly()->at('00:00');
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
