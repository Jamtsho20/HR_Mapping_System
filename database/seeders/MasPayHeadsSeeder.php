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
        DB::statement("INSERT INTO mas_pay_heads (name, code, payhead_type, calculation_method, calculated_on, amount, mas_pay_slab_id, mas_pay_group_id, account_head_id, formula, created_by, updated_by, created_at, updated_at) 
        VALUES 
            ('Medical Allowance', 'Medical ALL', 1, 2, 1, 12.00, 1, NULL, 1, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'),
            ('Project Allowance', 'Project ALL', 1, 6, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, '2024-08-20 08:11:10', '2024-09-17 17:10:02'),
            ('Critical Allowance', 'Critical ALL', 1, 4, 6, NULL, 1, 3, 1, NULL, 1, 1, '2024-08-20 08:11:10', '2024-09-19 09:33:12'),
            ('Overtime Allowance', 'Overtime ALL', 1, 6, NULL, NULL, NULL, NULL, 1, 'THEN [OVERTIME_HOURS] * [HOURLY_WAGE]', 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'),
            ('Corporate Allowance', 'Corporate ALL', 1, 4, 6, NULL, NULL, 4, 1, NULL, 1, NULL, NULL, NULL);
        ");

        DB::statement("INSERT INTO mas_pay_heads (name, code, payhead_type, calculation_method, calculated_on, amount, mas_pay_slab_id, mas_pay_group_id, account_head_id, formula, created_by, updated_by, created_at, updated_at) 
            VALUES 
            ('Salary Tax', 'TDS', 2, 3, 4, NULL, 1, NULL, 2, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'),
            ('Health Tax', 'H/Tax', 2, 6, NULL, NULL, NULL, NULL, 2, 'IF ([EMPLOYMENT_TYPE] == 1 OR [EMPLOYMENT_TYPE] == 3) \r\nTHEN ([GROSS_PAY] * 0.01)\r\nELSE\r\nTHEN 0\r\nENDIF', 1, 1, '2024-08-20 08:11:10', '2024-09-20 15:32:02'),
            ('Provident Fund', 'PF', 2, 6, NULL, NULL, 1, 1, 2, 'IF ([EMPLOYMENT_TYPE] == 1)\r\nTHEN ([BASIC_PAY] * 0.15)\r\nELSEIF ([EMPLOYMENT_TYPE] == 3)\r\nTHEN ([BASIC_PAY] * 0.15)\r\nELSEIF ([EMPLOYMENT_TYPE] == 4 OR [EMPLOYMENT_TYPE] == 5)\r\nTHEN ([BASIC_PAY] * 0.05)\r\nELSE\r\nTHEN 0\r\nENDIF', 1, 1, '2024-08-20 08:11:10', '2024-09-17 18:05:53'),
            ('Staff Initiative for Financial Assistance', 'SIFA', 2, 4, 1, NULL, NULL, 1, 2, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'),
            ('Group Savings Linked Insurance', 'GSLI', 2, 3, 1, NULL, 2, NULL, 2, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'),
            ('Device EMI', 'Device EMI', 2, 7, 0, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'),
            ( 'BOB Loan', 'BOB_Loan', 2, 7, 1, NULL, 1, 1, 2, NULL, 1, 1, NULL, '2024-09-20 14:09:08'),
            ( 'TBank Loan', 'TBank_Loan', 2, 7, 1, NULL, 1, 1, 2, NULL, 1, 1, NULL, '2024-09-20 14:11:44');");
    }
}
