<?php

namespace App\Console\Commands;

use App\Models\EmployeeLeave;
use App\Models\MasLeaveType;
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
    protected $description = 'Credit yearly leave for employees based on previous year leave records.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        // Fetch all leave types with their maximum allowable days
        $leaveTypes = MasLeaveType::pluck('max_days', 'id');

        DB::transaction(function () use ($leaveTypes, $currentYear, $previousYear) {
            // Process employees in chunks
            EmployeeLeave::whereYear('created_at', $previousYear)
                ->orderBy('mas_employee_id') // Ensure ordered processing by employee
                ->chunk(100, function ($previousYearRecords) use ($leaveTypes, $currentYear, $previousYear) {
                    foreach ($previousYearRecords as $record) {
                        // Skip if the record already exists for the current year
                        if (EmployeeLeave::where('mas_employee_id', $record->mas_employee_id)
                            ->where('mas_leave_type_id', $record->mas_leave_type_id)
                            ->whereYear('created_at', $currentYear)
                            ->exists()) {
                            continue;
                        }

                        // Calculate leave balances based on leave type
                        $openingBalance = $record->closing_balance ?? 0;
                        $entitlement = $leaveTypes[$record->mas_leave_type_id] ?? 0;
                        if ($record->mas_leave_type_id == CASUAL_LEAVE){
                            $entitlement = $record->current_entitlement ?? 0;
                        }
                        $closingBalance = $openingBalance + $entitlement;

                        if ($record->mas_leave_type_id == EARNED_LEAVE) {
                            // Special handling for earned leave: include previous casual leave balance
                            $previousCasualLeave = EmployeeLeave::where('mas_employee_id', $record->mas_employee_id)
                                ->where('mas_leave_type_id', CASUAL_LEAVE)
                                ->whereYear('created_at', $previousYear)
                                ->value('closing_balance') ?? 0;

                            $calculatedOpeningBalance = $openingBalance + $previousCasualLeave;
                            $openingBalance = min($calculatedOpeningBalance, 90.00);
                            $closingBalance = min($closingBalance, 90.00);
                        }

                        // $closingBalance = $openingBalance + $entitlement;

                        // Create the leave record for the current year
                        EmployeeLeave::create([
                            'mas_leave_type_id'   => $record->mas_leave_type_id,
                            'mas_employee_id'     => $record->mas_employee_id,
                            'opening_balance'     => $openingBalance,
                            'current_entitlement' => $entitlement,
                            'leaves_availed'      => 0,
                            'closing_balance'     => $closingBalance,
                            'created_by'          => $record->created_by,
                        ]);
                    }
                });
        });

        $this->info('Yearly leave crediting completed successfully based on previous year records.');
    }
}
