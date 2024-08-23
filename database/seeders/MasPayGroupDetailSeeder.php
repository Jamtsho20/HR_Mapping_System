<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasPayGroupDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
        INSERT INTO `mas_pay_group_details` (`id`, `mas_pay_group_id`, `mas_employee_group_id`, `mas_grade_id`, `calculation_method`, `amount`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 1, NULL, 1, 1, 500.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (2, 1, NULL, 7, 1, 325.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (3, 1, NULL, 6, 1, 200.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (4, 1, NULL, 4, 1, 250.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (5, 1, NULL, 5, 1, 250.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (6, 2, NULL, 1, 0, 1000.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (7, 2, NULL, 7, 0, 750.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (8, 2, NULL, 6, 0, 400.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (9, 2, NULL, 4, 0, 600.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (10, 2, NULL, 5, 0, 600.00, 1, NULL, "2023-10-01 18:00:00", NULL),
        (11, 3, 1, NULL, 3, 20.00, 1, NULL, "2023-10-01 18:00:00", NULL)
        ');
    }
}
