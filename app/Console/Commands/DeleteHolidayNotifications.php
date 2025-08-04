<?php

namespace App\Console\Commands;

use App\Models\SystemNotification;
use Illuminate\Console\Command;

class DeleteHolidayNotifications extends Command
{
    protected $signature = 'holiday:delete-alerts';
    protected $description = 'Delete all system notifications related to holiday alerts';

    public function handle()
    {
        $deleted = SystemNotification::where('title', 'Holiday Alert')->delete();

        if ($deleted) {
            $this->info("Holiday alert notifications deleted successfully.");
        } else {
            $this->info("No holiday alert notifications found.");
        }
    }
}
