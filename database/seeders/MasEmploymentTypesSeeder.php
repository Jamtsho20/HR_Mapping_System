<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasEmploymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_employment_types')->insert([
            ['id' => 1, 'name' => 'All', 'remarks' => 'Applicable to all ', 'created_by' => 1],
            ['id' => 2, 'name' => 'Regular', 'remarks' => 'Regular Employee', 'created_by' => 1],
            ['id' => 3, 'name' => 'Probation', 'remarks' => 'Probation Employee', 'created_by' => 1],
            ['id' => 4, 'name' => 'Long-term Contract(Executive Level)', 'remarks' => 'Long Term Contract Employee', 'created_by' => 1],
            ['id' => 5, 'name' => 'Long-term Contract(Technical Staff Group 2 Level)', 'remarks' => 'Long Term Contract Employee', 'created_by' => 1],
            ['id' => 6, 'name' => 'Long-term Contract(General Support Staff Group)', 'remarks' => 'Long Term Contract Employee', 'created_by' => 1],
            ['id' => 7, 'name' => 'Short-term Contract', 'remarks' => 'Short Term Contract Employee', 'created_by' => 1],
        ]);
    }
}

