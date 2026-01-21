<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
    INSERT INTO `mas_grades` (`id`, `name`, `created_by`) VALUES
    (1, "E0", 1),
    (4, "T1", 1),
    (5, "T2", 1),
    (6, "S", 1),
    (7, "P", 1),
    (9, "GSSG", 1),
    (11, "CTE", 1),
    (12, "STC", 1)
');
    }
}
