<?php

namespace App\Console\Commands;

use App\Models\EmployeeLeave;
use App\Models\MasLeaveType;
use App\Models\User;
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
    protected $description = 'Credit employee leave yearly';


    /**
     * Execute the console command.
     */

     public function handle()
     {
        EmployeeLeave::with(['leaveTypes.leavePolicy.leavePolicyPlan'])
            ->chunk(100, function ($employees) {
                foreach ($employees as $employee) {
                    foreach($employee->leaveTypes as $leaveType){
                        $creditFrequency = $leaveType->leavePolicy->leavePolicyPlan->credit_frequency;
                        // Handle crediting leaves based on frequency
                            if ($creditFrequency == 1) {
                                // Monthly leave credit
                                $this->creditLeave($employee, $leaveType, 'monthly');
                            } else {
                                // Yearly leave credit
                                $this->creditLeave($employee, $leaveType, 'yearly');
                            }
                    }
                }
            });
     }
    // public function handle()
    // {
    //     DB::beginTransaction();
    //     try {
    //         $leaveTypeId = $this->argument('leave_type_id'); // leave_type_id parameter passed from kernel.php while executing schedule
    //         $gender = $this->argument('gender'); //gender parameter passed from kernel.php while executing schedule
    //         $currentYear = date('Y');
            
    //         //get leave type along with its policies and rule based on $leaveTypeId
    //         $leaveType = MasLeaveType::with(['leavePolicy.yearEnd' => function($query) {
    //             $query->whereStatus(1)->where('is_information_only', 1);
    //         }, 
    //         'leavePolicy.leavePolicyPlan.LeavePolicyRule' => function($query1){
    //             $query1->whereStatus(1)->where('is_information_only', 1);
    //         }])->where('mas_leave_type_id', $leaveTypeId)->firstOrFail();

    //         // Query employees matching these criteria gradeSteps and employmentType
    //         $leavePolicyRules = $leaveType->leavePolicy->leavePolicyPlan->LeavePolicyRule ?? [];
    //         $employees = collect();
    //         foreach ($leavePolicyRules as $rule) {
    //             // For each rule, fetch the matching employees with both grade_step and employment_type
    //             $matchedEmployees = User::whereHas('empJob', function($query) use ($rule) {
    //                 $query->where('mas_grade_step_id', $rule->mas_grade_step_id)
    //                       ->where('mas_employment_type_id', $rule->mas_employment_type_id);
    //             })
    //             ->where('status', 1)
    //             ->where('is_active', 1)
    //             ->when($gender != 3, function($query) use ($gender) {
    //                     // Filter employees by gender if not "all" (represented by 3)
    //                     return $query->where('gender', $gender);
    //                 })
    //             ->get();

    //             // Add the `duration` field to each employee from the corresponding rule
    //             $matchedEmployees->each(function($employee) use ($rule) {
    //                 $employee->duration = $rule->duration; // Assuming 'duration' is a field in the rule
    //             });
    //             // Add the matched employees to the collection
    //             $employees = $employees->merge($matchedEmployees);
    //         }
    //         // Optionally, you can remove duplicates by employee ID
    //         $uniqueEmployees = $employees->unique('id');

    //         // Insert into employee_leaves table based on leave policy
    //         foreach ($uniqueEmployees as $employee) {
    //             $employeeLeave = new EmployeeLeave();
    //             $previousEmpLeave = EmployeeLeave::where('mas_employee_id', $employee->id)
    //                 ->where('mas_leave_type_id', $leaveTypeId)
    //                 ->whereYear('created_at', $currentYear - 1)
    //                 ->first();

    //             $openingBalance = 0;
    //             $carryOver = $leaveType->leavePolicy->yearEnd->allow_carry_over ?? false;
    //             $carryForward = $leaveType->leavePolicy->yearEnd->carry_forward_to_el ?? false;

    //             if ($carryOver && $previousEmpLeave) { //here checks if it carry over limit and opening bal same the it will take carry over limit as default if not it will take the minimum value from 2
    //                 $carryOverLimit = (int) $leaveType->leavePolicy->yearEnd->carry_over_limit;
    //                 $openingBalance = min((int) $previousEmpLeave->closing_balance, $carryOverLimit);
    //             } elseif ($carryForward && $previousEmpLeave) {
    //                 $carryForwardLimit = (int) $leaveType->leavePolicy->yearEnd->carry_forward_limit;
    //                 $openingBalance = min((int) $previousEmpLeave->closing_balance, $carryForwardLimit);
    //             }

    //             // If credit frequency is 1, update the existing record
    //             // If credit frequency is 2, insert a new record
    //             $creditFrequency = $leaveType->leavePolicy->leavePolicyPlan->credit_frequency;

    //             // Determine if it's a new year
    //             $isNewYear = $currentYear != date('Y', strtotime($previousEmpLeaveLastYear->created_at ?? 'now'));

    //             if ($creditFrequency == 1) { // Monthly - Update existing record
    //                 if (!$isNewYear) {
    //                     // Update existing record for the current year
    //                     $previousEmpLeave->opening_balance = $openingBalance;
    //                     $previousEmpLeave->current_entitlement = (int) $employee->duration;
    //                     $previousEmpLeave->closing_balance = (int) $employee->duration + $openingBalance;
    //                     $previousEmpLeave->save();
    //                 } else {
    //                     // If it's a new year, create a new record
    //                     $employeeLeave = new EmployeeLeave();
    //                     $employeeLeave->mas_employee_id = $employee->id;
    //                     $employeeLeave->mas_leave_type_id = $leaveType->id;
    //                     $employeeLeave->opening_balance = $openingBalance;
    //                     $employeeLeave->current_entitlement = (int) $employee->duration;
    //                     $employeeLeave->leaves_availed = 0;
    //                     $employeeLeave->closing_balance = (int) $employee->duration + $openingBalance;
    //                     $employeeLeave->created_by = $employee->created_by;
                
    //                     $employeeLeave->save();
    //                 }
    //             } else { //  If credit frequency is 2 (yearly) Create a new record regardless of previous records
    //                 $employeeLeave = new EmployeeLeave();
    //                 $employeeLeave->mas_employee_id = $employee->id;
    //                 $employeeLeave->mas_leave_type_id = $leaveType->id;
    //                 $employeeLeave->opening_balance = $openingBalance;
    //                 $employeeLeave->current_entitlement = (int) $employee->duration;
    //                 $employeeLeave->leaves_availed = 0;
    //                 $employeeLeave->closing_balance = (int) $employee->duration + $openingBalance;
    //                 $employeeLeave->created_by = $employee->created_by;

    //                 $employeeLeave->save();
    //             }
    //         }

    //         DB::commit();
    //         $this->info('Leave successfully credited to all employees.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         $this->error('Failed to credit leave: ' . $e->getMessage());
    //     }
    // }
}
