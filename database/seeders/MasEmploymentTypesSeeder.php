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
            ['id' => 1, 'name' => 'Full-Time', 'remarks' => 'Full-time employment', 'created_by' => 1],
            ['id' => 2, 'name' => 'Part-Time', 'remarks' => 'Part-time employment', 'created_by' => 1],
            ['id' => 3, 'name' => 'Contract', 'remarks' => 'Contractual employment', 'created_by' => 1],
            ['id' => 4, 'name' => 'Internship', 'remarks' => 'Internship or training', 'created_by' => 1],
            ['id' => 5, 'name' => 'Freelance', 'remarks' => 'Freelance or consultancy', 'created_by' => 1],
        ]);
    }
}
