<?php

namespace App\Console\Commands;

use App\Models\Delegation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDelegations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-delegations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset delegation as and when time expires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // Get all expired active delegations
        $expiredDelegations = Delegation::where('end_date', '<=', $today)
            ->where('status', 1)
            ->get();
        
        foreach ($expiredDelegations as $delegation) {
            // Update the delegation status
            $delegation->status = 0;
            $delegation->save();
        
            // Update the corresponding mas_employee_role record
            DB::table('mas_employee_roles')
                ->where('mas_employee_id', $delegation->delegator_id)
                ->where('role_id', $delegation->role_id)
                ->update(['has_delegation' => 0]);
        }
        $this->info('Delegation successfully updated.');
        
    }
}
