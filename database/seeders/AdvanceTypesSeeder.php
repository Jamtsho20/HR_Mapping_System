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
           INSERT INTO `advance_types` (`advancetype`, `status`, `created_by`) VALUES
            ("Advance to Staff", 1 , 1),
            ("DSA Advance(Tour)", 1, 1),
            ("Electricity Imprest Advance", 1, 1),
            ("Gadget EMI", 1, 1),
            ("Imprest Advance", 1, 1),
            ("Salary Advance", 1, 1),
            ("SIFA LOAN", 1, 1);

        ');
    }
}
