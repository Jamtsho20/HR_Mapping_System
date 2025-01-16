<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasEmploymentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_employee_groups')->insert([
            ['id' => 1, 'name' => 'Critical Staff','description'=>'NA', 'created_by' => 1],
        ]);
    }
}
