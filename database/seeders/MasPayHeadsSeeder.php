<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class MasPayHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_pay_heads')->insert([
            [
                'name' => 'Tax Deducted at source',
                'code' => 'TDS',
                'payhead_type' => 2, // Deduction
                'calculation_method' => 3,
                'calculated_on' => 3,
                'amount' => null,
                'mas_pay_slab_id' => null, 
                'mas_pay_group_id' => null, 
                'account_head_id' => 1, 
                'formula' => null,
                'created_by' => 1, 
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medical Allowance',
                'code' => 'MA',
                'payhead_type' => 1, 
                'calculation_method' => 2, 
                'calculated_on' => 1, 
                'amount' => 12.00,
                'mas_pay_slab_id' => 2, 
                'mas_pay_group_id' => null, 
                'account_head_id' => 2, 
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
                'account_head_id' => 3, 
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
                'calculation_method' => 5, 
                'calculated_on' => 1, 
                'amount' => 15.00,
                'mas_pay_slab_id' => null, 
                'mas_pay_group_id' => null, 
                'account_head_id' => 3, 
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
                'amount' =>null,
                'mas_pay_slab_id' => null, 
                'mas_pay_group_id' => null, 
                'account_head_id' => 3, 
                'formula' => 'IF ([MONTHS_SINCE_REGULARISATION] >= 12)
                            THEN (0.85 * [PAY_SCALE_BASE_PAY])
                            ELSEIF ([MONTHS_SINCE_REGULARISATION] > 0)
                            THEN (0.425 * [PAY_SCALE_BASE_PAY])
                            ELSE
                            THEN 0
                            ENDIF', 
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
                'account_head_id' => 3, 
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
                'account_head_id' => 3, 
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
                'account_head_id' => 3, 
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
                'account_head_id' => 3, 
                'formula' => 'THEN [OVERTIME_HOURS] * [HOURLY_WAGE]', 
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
                'calculated_on' => null, 
                'amount' => null,
                'mas_pay_slab_id' => null, 
                'mas_pay_group_id' => null, 
                'account_head_id' => 3, 
                'formula' => null, 
                'created_by' => 1, 
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);
    }

}


