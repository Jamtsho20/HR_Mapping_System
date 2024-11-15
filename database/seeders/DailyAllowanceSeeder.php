<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyAllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
            INSERT INTO `mas_daily_allowances` (`id`, `mas_grade_id`, `da_in_country`, `da_india_capital`, `da_india_non_capital`, `da_third_country`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
            (1, 1, 3500, NULL, NULL, NULL, 1, NULL, "2023-10-01 18:00:00", NULL),
            (2, 7, 1800, NULL, NULL, NULL, 1, NULL, "2023-10-01 18:00:00", NULL),
            (3, 4, 1250, NULL, NULL, NULL, 1, NULL, "2023-10-01 18:00:00", NULL),
            (4, 5, 1000, NULL, NULL, NULL, 1, NULL, "2023-10-01 18:00:00", NULL),
            (5, 9, 700, NULL, NULL, NULL, 1, NULL, "2023-10-01 18:00:00", NULL)
        ');
    }
}
