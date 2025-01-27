<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasPayHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO mas_pay_heads (
                id,
                name,
                code,
                general_ledger_code,
                payhead_type,
                calculation_method,
                calculated_on,
                amount,
                mas_pay_slab_id,
                mas_pay_group_id,
                account_head_id,
                formula,
                created_by,
                updated_by,
                created_at,
                updated_at
            )
            VALUES
                (
                    1, 'House Allowance', '52315', 'House Allowance', 1, 7, NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, '2024-12-13 16:19:12', '2024-12-13 16:19:12'
                ),
                (
                    2, 'Medical Allowance', '52711', 'Medical Allowance', 1, 2, 1, 12.00, 1, NULL, 1, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'
                ),
                (
                    3, 'Additional Work Allowance', '52338', 'Add. Work Allowance', 1, 7, NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, '2024-12-13 16:23:17', '2024-12-13 16:23:17'
                ),
                (
                    4, 'Cash Allowance', '52316', 'Cash Allowance', 1, 7, NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, '2024-12-13 16:22:23', '2024-12-13 16:22:23'
                ),
                (
                    5, 'Corporate Allowance', '52336', 'Corporate Allowance', 1, 4, 6, NULL, NULL, 5, 1, NULL, 1, NULL, NULL, NULL
                ),
                (
                    6, 'Difficulty Allowance', '52335', 'Difficulty Allowance', 1, 7, NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, '2024-12-14 10:06:58', '2024-12-14 10:06:58'
                ),
                (
                    7, 'Critical Allowance', '52337', 'Critical Allowance', 1, 4, 1, NULL, NULL, 3, 1, NULL, 1, 1, '2024-08-20 08:11:10', '2024-09-19 09:33:12'
                ),
                (
                    8, 'Advance Salary', '12345', 'Adv. Salary', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-24 11:14:56', '2024-12-24 11:14:56'
                ),
                (
                    9, 'Advance Staff', '12345', 'Adv. Staff', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-24 11:14:56', '2024-12-24 11:14:56'
                ),
                (
                    10, 'Provident Fund', '23181', 'PF Contr', 2, 6, NULL, NULL, NULL, NULL, 2, 'IF ([EMPLOYMENT_TYPE] == 2)\r\nTHEN ([BASIC_PAY] * 0.15)\r\nELSEIF ([EMPLOYMENT_TYPE] == 4)\r\nTHEN ([BASIC_PAY] * 0.15)\r\nELSEIF ([EMPLOYMENT_TYPE] == 9)\r\nTHEN ([BASIC_PAY] * 0.05)\r\nELSE\r\nTHEN 0\r\nENDIF', 1, 1, '2024-08-20 08:11:10', '2024-09-17 18:05:53'
                ),
                (
                    11, 'Staff Salary Saving Scheme', '23191', 'SSSS', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-13 16:35:31', '2024-12-13 16:35:31'
                ),
                (
                    12, 'Staff Initiative for Financial Assistance', '23209', 'SIFA', 2, 6, NULL, NULL, NULL, NULL, 2, 'IF ([SIFA_MEMBER] == 1)\r\nIF ([GRADE] == \'E0\')\r\nTHEN (400)\r\nENDIF\r\nIF ([GRADE] == \'P\')\r\nTHEN (325)\r\nENDIF\r\nIF ([GRADE] == \'S\')\r\nTHEN (125)\r\nENDIF\r\nIF ([GRADE] == \'T1\')\r\nTHEN (225)\r\nENDIF\r\nIF ([GRADE] == \'T2\')\r\nTHEN (225)\r\nENDIF\r\nIF ([GRADE] == \'GSSG\')\r\nTHEN (125)\r\nENDIF\r\nIF ([GRADE] == \'T\')\r\nTHEN (225)\r\nENDIF\r\nELSE\r\nTHEN (0)\r\nENDIF', 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'
                ),
                (
                    13, 'Health Tax', '23183', 'H/Tax', 2, 6, NULL, NULL, NULL, NULL, 2, 'THEN ([GROSS_PAY] * 0.01)', 1, 1, '2024-08-20 08:11:10', '2024-09-20 15:32:02'
                ),
                (
                    14, 'Salary Tax', '23173', 'Salary Tax', 2, 3, 4, NULL, 1, NULL, 2, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'
                ),
                (
                    15, 'Group Savings Linked Insurance', '23192', 'GSLI', 2, 3, 1, NULL, 2, NULL, 2, NULL, 1, NULL, '2024-08-20 08:11:10', '2024-08-20 08:11:10'
                ),
                (
                    16, 'Samsung Deduction', '23194', 'Samsung Ded', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-13 16:44:02', '2024-12-13 16:44:02'
                ),
                (
                    17, 'Loan BNB', '23194', 'Loan BNB', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-13 16:44:02', '2024-12-13 16:44:02'
                ),
                (
                    18, 'Loan NPPF', '23198', 'Loan NPPF', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-14 15:53:48', '2024-12-14 15:53:48'
                ),
                (
                    19, 'Loan BDFC', '23189', 'Loan BDFC', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-13 16:28:38', '2024-12-13 16:28:38'
                ),
                (
                    20, 'Loan TBank', '23190', 'Loan TBank', 2, 7, 1, NULL, 1, 1, 2, NULL, 1, 1, NULL, '2024-09-20 14:11:44'
                ),
                (
                    21, 'Loan RICB', '23209', 'Loan RICB', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-13 16:44:49', '2024-12-13 16:44:49'
                ),
                (
                    22, 'Loan DPNB', '23196', 'Loan DPNB', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-14 09:58:02', '2024-12-14 09:58:02'
                ),
                (
                    23, 'Loan BOB', '23193', 'Loan BOB', 2, 7, 1, NULL, 1, 1, 2, NULL, 1, 1, NULL, '2024-09-20 14:09:08'
                ),
                (
                    24, 'Loan SIFA', '54321', 'Loan SIFA', 2, 7, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, '2024-12-24 11:27:48', '2024-12-24 11:27:48'
                )

        ");

    }
}
