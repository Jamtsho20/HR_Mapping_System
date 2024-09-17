<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasPayHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the account head IDs where the names are 'Deductions' and 'Allowance'
        $deductionAccountHead = DB::table('mas_acc_account_heads')->where('name', 'Deduction')->first();
        $allowanceAccountHead = DB::table('mas_acc_account_heads')->where('name', 'Allowance')->first();

        // Check if the account heads exist
        if ($deductionAccountHead) {
            DB::table('mas_pay_heads')->insert([
                [
                    'name' => 'Tax Deducted at Source',
                    'code' => 'TDS',
                    'payhead_type' => 2,
                    'calculation_method' => 3,
                    'calculated_on' => 3,
                    'amount' => null,
                    'mas_pay_slab_id' => 1,
                    'mas_pay_group_id' => null,
                    'account_head_id' => $deductionAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Health Tax',
                    'code' => 'HC',
                    'payhead_type' => 2,
                    'calculation_method' => 5,
                    'calculated_on' => 2,
                    'amount' => 1.00,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' =>  $deductionAccountHead->id,
                    'formula' => 'null',
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Provident Fund',
                    'code' => 'PF',
                    'payhead_type' => 2,
                    'calculation_method' => 6,
                    'calculated_on' => 1,
                    'amount' => 15.00,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' =>  $deductionAccountHead->id,
                    'formula' => "IF ([mas_employment_types].[name] = 'Regular')
                    THEN (EMPLOYEE_PF = ROUND([BASIC_PAY] * 0.15))
                    THEN (EMPLOYER_PF = ROUND([BASIC_PAY] * 0.10))
                ELSEIF ([mas_employment_types].[name] = 'Contract')
                    THEN (EMPLOYEE_PF = ROUND([BASIC_PAY] * 0.15))
                    THEN (EMPLOYER_PF = ROUND([BASIC_PAY] * 0.15))
                ELSEIF ([mas_employment_types].[name] = 'Consolidate' OR [mas_employment_types].[name] = 'Support Contract')
                    THEN (EMPLOYEE_PF = ROUND([BASIC_PAY] * 0.05))
                    THEN (EMPLOYER_PF = ROUND([BASIC_PAY] * 0.05))
                ELSE
                    THEN (EMPLOYEE_PF = 0)
                    THEN (EMPLOYER_PF = 0)
            ENDIF",
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Staff Initiative for Financial Assistance',
                    'code' => 'SIFA',
                    'payhead_type' => 2,
                    'calculation_method' => 4,
                    'calculated_on' => 1,
                    'amount' => null,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' => $deductionAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Group Savings Linked Insurance',
                    'code' => 'GSLI',
                    'payhead_type' => 2,
                    'calculation_method' => 3,
                    'calculated_on' => 1,
                    'amount' => null,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' => $deductionAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Device EMI',
                    'code' => 'Device_EMI',
                    'payhead_type' => 2,
                    'calculation_method' => 7,
                    'calculated_on' => 0,
                    'amount' => null,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' => $deductionAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

            ]);
        } else {

            echo "Account head 'Deduction' not found!";
        }

        if ($allowanceAccountHead) {
            DB::table('mas_pay_heads')->insert([
                [
                    'name' => 'Medical Allowance',
                    'code' => 'MA',
                    'payhead_type' => 1,
                    'calculation_method' => 2,
                    'calculated_on' => 1,
                    'amount' => 12.00,
                    'mas_pay_slab_id' => 1,
                    'mas_pay_group_id' => null,
                    'account_head_id' => $allowanceAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Project Allowance',
                    'code' => 'PA',
                    'payhead_type' => 1,
                    'calculation_method' => 6,
                    'calculated_on' => 5,
                    'amount' => null,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' => $allowanceAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Critical Allowance',
                    'code' => 'CA',
                    'payhead_type' => 1,
                    'calculation_method' => 4,
                    'calculated_on' => 6,
                    'amount' => null,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' =>  $allowanceAccountHead->id,
                    'formula' => null,
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Overtime Allowance',
                    'code' => 'OA',
                    'payhead_type' => 1,
                    'calculation_method' => 6,
                    'calculated_on' => 5,
                    'amount' => 0.00,
                    'mas_pay_slab_id' => null,
                    'mas_pay_group_id' => null,
                    'account_head_id' =>  $allowanceAccountHead->id,
                    'formula' => 'THEN [OVERTIME_HOURS] * [HOURLY_WAGE]',
                    'created_by' => 1,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        } else {

            echo "Account head 'Allowance' not found!";
        }
    }
}
