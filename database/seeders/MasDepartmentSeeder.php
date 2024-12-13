<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('mas_departments')->insert([
        ['short_name' => 'HRAD', 'name' => 'Human Resource and Administration Department', 'code' => 105, 'created_by' => 1],
        ['short_name' => 'Marketing', 'name' => 'Marketing Department', 'code' => 108, 'created_by' => 1],
        ['short_name' => 'FD', 'name' => 'Finance Department', 'code' => 101, 'created_by' => 1],
        ['short_name' => 'AND', 'name' => 'Access Network Department', 'code' => 102, 'created_by' => 1],
        ['short_name' => 'CNCS', 'name' => 'Core Network and Carrier Services Department', 'code' => 104, 'created_by' => 1],
        ['short_name' => 'SPPD', 'name' => 'Strategic Planning and Projects Department', 'code' => 106, 'created_by' => 1],
        ['short_name' => 'MIS', 'name' => 'Management Information System Department', 'code' => 103, 'created_by' => 1],
        ['short_name' => 'IAU', 'name' => 'Internal Audit Unit', 'code' => 107, 'created_by' => 1],
    ]);
}
}
