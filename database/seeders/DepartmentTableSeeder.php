<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_departments')->insert([
            ['short_name' => 'MIS', 'name' => 'Management Information System', 'created_by' => 1],
            ['short_name' => 'HRAD', 'name' => 'Human Resource & Administration', 'created_by' => 1],
            ['short_name' => 'CNCS', 'name' => 'Core Network and Carrier Services', 'created_by' => 1],
            ['short_name' => 'AND', 'name' => 'Access Network', 'created_by' => 1],
            ['short_name' => 'SPPD', 'name' => 'Strategic Planning and Projects', 'created_by' => 1],
            ['short_name' => 'IAU', 'name' => 'Internal Audit Unit', 'created_by' => 1],
            ['short_name' => 'CC', 'name' => 'Contact Center Unit', 'created_by' => 1],
            ['short_name' => 'FD', 'name' => 'Finance', 'created_by' => 1],
            ['short_name' => 'COM', 'name' => 'Commercial', 'created_by' => 1],
        ]);
    }
}
