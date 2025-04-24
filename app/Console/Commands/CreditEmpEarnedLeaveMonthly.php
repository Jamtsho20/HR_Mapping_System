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
                        $calculatedClosingBalance = $leave->opening_balance 
                                                    + $leave->current_entitlement 
                                                    - $leave->leaves_availed;
                        $leave->closing_balance = min($calculatedClosingBalance, 90.00);
                        $leave->save();
                    }
                });
            });

        $this->info('Monthly earned leave credit completed successfully in batches.');
    }

    // public function handle()
    // {
    //     $currentMonth = now()->month;
    //     // $previousMonth = now()->subMonth()->month;
    //     // $previousMonthYear = now()->subMonth()->year;
    //     $currentYear = now()->year;

    //     EmployeeLeave::where('mas_leave_type_id', EARNED_LEAVE)
    //         ->chunk(100, function ($empLeaves) use ($currentMonth, $currentYear) {
    //             DB::transaction(function () use ($empLeaves, $currentMonth, $currentYear) {
    //                 foreach ($empLeaves as $empLeave) {
    //                     $employeeId = $empLeave->employee_id;

    //                     // Total leave days in previous month
    //                     $totalLeaveDays = DB::table('leave_applications')
    //                         ->where('created_by', $employeeId)
    //                         ->where('status', 3) // Approved
    //                         ->where('updated_at', $currentMonth)
    //                         ->where('updated_at', $currentYear)
    //                         ->where(function ($query) use ($currentMonth, $currentYear) {
    //                             $query->whereMonth('from_date', $currentMonth)
    //                                 ->whereYear('from_date', $currentYear)
    //                                 ->orWhere(function ($q) use ($currentMonth, $currentYear) {
    //                                     $q->whereMonth('to_date', $currentMonth)
    //                                         ->whereYear('to_date', $currentYear);
    //                                 });
    //                         })
    //                         ->sum('no_of_days');

    //                     if ($totalLeaveDays > 30) {
    //                         // Skip crediting earned leave
    //                         continue;
    //                     }

    //                     // Credit earned leave
    //                     $empLeave->current_entitlement += EARNED_LEAVE_CREDIT_AMOUNT;

    //                     $calculatedClosingBalance = $empLeave->opening_balance 
    //                         + $empLeave->current_entitlement 
    //                         - $empLeave->leaves_availed;

    //                     $empLeave->closing_balance = min($calculatedClosingBalance, 90.00);
    //                     $empLeave->save();
    //                 }
    //             });
    //         });

    //     $this->info('Monthly earned leave credit completed (excluding employees with >30 leave days last month).');
    // }

}
