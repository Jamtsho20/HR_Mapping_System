<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvanceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
           INSERT INTO `mas_advance_types` (`name`, `code`, `status`, `created_by`) VALUES
            ("Advance to Staff", "36117", 1 , 1),
            ("DSA Advance(Tour)", "36145", 1, 1),
            ("Electricity Imprest Advance", "36127", 1, 1),
            ("Gadget EMI", "34605", 1, 1),
            ("Imprest Advance", "36126", 1, 1),
            ("Salary Advance", "36144", 1, 1),
            ("SIFA LOAN", "SIFA", 1, 1);
        ');
    }
}
