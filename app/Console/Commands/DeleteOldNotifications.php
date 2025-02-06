<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-notifications';

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
         // Delete holiday alerts older than 1 day
         DB::table('system_notifications')
         ->where('title', 'Holiday Alert')
         ->where('created_at', '<', Carbon::now()->subDay())
         ->delete();

     $this->info('Old holiday alerts deleted successfully.');
    }
}
