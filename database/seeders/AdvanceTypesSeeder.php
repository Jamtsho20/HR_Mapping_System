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
            ("Advance to Staff", "ADV_Staff", 1 , 1),
            ("DSA Advance(Tour)", "DSA_ADV", 1, 1),
            ("Electricity Imprest Advance", "ELC_IMP_ADV", 1, 1),
            ("Gadget EMI", "GAD_EMI", 1, 1),
            ("Imprest Advance", "IMP_ADV", 1, 1),
            ("Salary Advance", "36144", 1, 1),
            ("SIFA LOAN", "SIFA", 1, 1);
        ');
    }
}
