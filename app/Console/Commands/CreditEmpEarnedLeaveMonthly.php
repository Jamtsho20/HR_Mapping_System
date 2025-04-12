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
                        $calculatedCurrentEntitlement = $leave->current_entitlement + EARNED_LEAVE_CREDIT_AMOUNT;
                        $leave->current_entitlement += EARNED_LEAVE_CREDIT_AMOUNT; // Add 2.5 days per month
                        $calculatedClosingBalance = $leave->opening_balance 
                                                    + $leave->current_entitlement 
                                                    - $leave->leaves_availed;
                        $leave->closing_balance = min($calculatedClosingBalance, 90.00);
                        // $leave->current_entitlement = min($calculatedCurrentEntitlement, 90.00);
                        // Save updated leave
                        $leave->save();
                    }
                });
            });

        $this->info('Monthly earned leave credit completed successfully in batches.');
    }

    // public function handle()
    // {
    //     $previousMonth = now()->subMonth()->month;
    //     $previousMonthYear = now()->subMonth()->year;

    //     EmployeeLeave::where('mas_leave_type_id', EARNED_LEAVE)
    //         ->chunk(100, function ($leaves) use ($previousMonth, $previousMonthYear) {
    //             DB::transaction(function () use ($leaves, $previousMonth, $previousMonthYear) {
    //                 foreach ($leaves as $leave) {
    //                     $employeeId = $leave->employee_id;

    //                     // Total leave days in previous month
    //                     $totalLeaveDays = DB::table('leave_applications')
    //                         ->where('created_by', $employeeId)
    //                         ->where('status', 2) // Approved
    //                         ->where(function ($query) use ($previousMonth, $previousMonthYear) {
    //                             $query->whereMonth('from_date', $previousMonth)
    //                                 ->whereYear('from_date', $previousMonthYear)
    //                                 ->orWhere(function ($q) use ($previousMonth, $previousMonthYear) {
    //                                     $q->whereMonth('to_date', $previousMonth)
    //                                         ->whereYear('to_date', $previousMonthYear);
    //                                 });
    //                         })
    //                         ->sum('no_of_days');

    //                     if ($totalLeaveDays > 30) {
    //                         // Skip crediting earned leave
    //                         continue;
    //                     }

    //                     // Credit earned leave
    //                     $leave->current_entitlement += EARNED_LEAVE_CREDIT_AMOUNT;

    //                     $calculatedClosingBalance = $leave->opening_balance 
    //                         + $leave->current_entitlement 
    //                         - $leave->leaves_availed;

    //                     $leave->closing_balance = min($calculatedClosingBalance, 90.00);
    //                     $leave->save();
    //                 }
    //             });
    //         });

    //     $this->info('Monthly earned leave credit completed (excluding employees with >30 leave days last month).');
    // }

}
