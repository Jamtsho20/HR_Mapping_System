<?php

namespace App\Console\Commands;

use App\Models\EmployeeLeave;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreditEmpEarnedLeaveMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit-emp-earned-leaves-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit employee earned leave monthly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Process EmployeeLeave records in batches to handle large datasets
        EmployeeLeave::where('mas_leave_type_id', EARNED_LEAVE)
            ->chunk(100, function ($leaves) {
                DB::transaction(function () use ($leaves) {
                    foreach ($leaves as $leave) {
                        // Credit leave based on type
                        $leave->current_entitlement += EARNED_LEAVE_CREDIT_AMOUNT; // Add 2.5 days per month
                        $leave->closing_balance = $leave->opening_balance 
                            + $leave->current_entitlement 
                            - $leave->leaves_availed;

                        // Save updated leave
                        $leave->save();
                    }
                });
            });

        $this->info('Monthly earned leave credit completed successfully in batches.');
    }
}
