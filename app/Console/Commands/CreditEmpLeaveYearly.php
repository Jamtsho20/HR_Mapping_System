<?php

namespace App\Console\Commands;

use App\Models\EmployeeLeave;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreditEmpLeaveYearly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit-emp-leaves-yearly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit yearly leave for employees based on leave type policies.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = now()->year;

        // Process employee leave records in chunks to handle large datasets
        EmployeeLeave::whereYear('created_at', $currentYear - 1)
            ->orderBy('mas_employee_id') // Optional: ensures grouped processing by employee
            ->chunk(100, function ($previousYearRecords) use ($currentYear) {
                DB::transaction(function () use ($previousYearRecords, $currentYear) {
                    foreach ($previousYearRecords as $record) {
                        // Check if record already exists for the current year
                        $exists = EmployeeLeave::where('mas_leave_type_id', $record->mas_leave_type_id)
                            ->where('mas_employee_id', $record->mas_employee_id)
                            ->whereYear('created_at', $currentYear)
                            ->exists();

                        if ($exists) {
                            continue; // Skip if record exists
                        }

                        if ($record->mas_leave_type_id == CASUAL_LEAVE) {
                            // Credit casual leave
                            EmployeeLeave::create([
                                'mas_leave_type_id'     => $record->mas_leave_type_id,
                                'mas_employee_id'       => $record->mas_employee_id,
                                'opening_balance'       => 0,
                                'current_entitlement'   => CASUAL_LEAVE_CREDIT_AMOUNT,
                                'leaves_availed'        => 0,
                                'closing_balance'       => CASUAL_LEAVE_CREDIT_AMOUNT,
                                'created_by'            => $record->created_by,
                            ]);
                        } else {
                            // Calculate opening balance for earned leave
                            $previousCasualLeave = EmployeeLeave::where('mas_employee_id', $record->mas_employee_id)
                                ->where('mas_leave_type_id', CASUAL_LEAVE)
                                ->whereYear('created_at', $currentYear - 1)
                                ->value('closing_balance');

                            $previousEarnedLeave = EmployeeLeave::where('mas_employee_id', $record->mas_employee_id)
                                ->where('mas_leave_type_id', EARNED_LEAVE)
                                ->whereYear('created_at', $currentYear - 1)
                                ->value('closing_balance');

                            $openingBalance = ($previousCasualLeave ?? 0) + ($previousEarnedLeave ?? 0);
                            $entitlement = 0;
                            $closingBalance = $openingBalance + $entitlement;

                            // Credit earned leave
                            EmployeeLeave::create([
                                'mas_leave_type_id'     => $record->mas_leave_type_id,
                                'mas_employee_id'       => $record->mas_employee_id,
                                'opening_balance'       => $openingBalance,
                                'current_entitlement'   => $entitlement,
                                'leaves_availed'        => 0,
                                'closing_balance'       => $closingBalance,
                                'created_by'            => $record->created_by,
                            ]);
                        }
                    }
                });
            });

        $this->info('Yearly leave credit completed successfully in batches.');
    }
}
