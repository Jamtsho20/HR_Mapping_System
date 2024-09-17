<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasPayGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
        INSERT INTO `mas_pay_group_details` (`mas_pay_group_id`, `mas_employee_group_id`, `mas_grade_id`, `calculation_method`, `amount`, `created_by`, `edited_by`, `created_at`, `updated_at`) VALUES
        (1, NULL, 18, 1, 500.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (1, NULL, 24, 1, 325.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (1, NULL, 23, 1, 200.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (1, NULL, 21, 1, 250.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (1, NULL, 22, 1, 250.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (2, NULL, 18, 0, 1000.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (2, NULL, 24, 0, 750.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (2, NULL, 23, 0, 400.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (2, NULL, 21, 0, 600.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (2, NULL, 22, 0, 600.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (3, NULL, NULL, 3, 20.00, 1, NULL, "2023-10-01 18:00:00", NULL)
        ');
    }
}
