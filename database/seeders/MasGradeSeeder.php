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
            INSERT INTO `mas_grades` (`name`, `created_by`) VALUES
            ("E0", 1),
            ("T1", 1),
            ("T2", 1),
            ("S", 1),
            ("P", 1),
            ("GSSG", 1);
            ("STC", 1);

        ');
    }
}
