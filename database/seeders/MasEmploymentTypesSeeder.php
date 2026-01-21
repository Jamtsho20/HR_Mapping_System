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
            ['name' => 'Regular', 'remarks' => 'Regular Employee', 'created_by' => 1],
            ['name' => 'Contract', 'remarks' => 'Contract Employee', 'created_by' => 1],
            [ 'name' => 'MasterRoll', 'remarks' => 'Master Roll Employee', 'created_by' => 1],
        ]);
    }
}

