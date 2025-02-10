<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // ✅ Import the DB facade

class DeleteOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holiday:clear-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete holiday alerts older than one day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Delete holiday alerts older than 1 day
            $deletedCount = DB::table('system_notifications')
                ->where('title', 'Holiday Alert')
                ->where('created_at', '<', Carbon::now()->subDay())
                ->delete();

            if ($deletedCount > 0) {
                $this->info("Deleted $deletedCount old holiday alerts.");
            } else {
                $this->info("No old holiday alerts found.");
            }

        } catch (\Exception $e) {
            $this->error("Error deleting old holiday alerts: " . $e->getMessage());
        }
    }
}
