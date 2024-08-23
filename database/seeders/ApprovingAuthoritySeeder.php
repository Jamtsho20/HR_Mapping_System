<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovingAuthoritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('approving_authorities')->insert([
            ['id' => 1, 'name' => 'Immediate Head', 'description' => 'Immediate Head', 'has_employee_field' => 0, 'created_by' => 1],
            ['id' => 2, 'name' => 'Department Head', 'description' => 'Department Head', 'has_employee_field' => 0, 'created_by' => 1],
            ['id' => 3, 'name' => 'Management', 'description' => 'Management', 'has_employee_field' => 1, 'created_by' => 1],
            ['id' => 4, 'name' => 'Human Resource', 'description' => 'Human Resource', 'has_employee_field' => '1', 'created_by' => 1],
            ['id' => 5, 'name' => 'Finance Head', 'description' => 'Finance Head', 'has_employee_field' => 1, 'created_by' => 1],
            ['id' => 6, 'name' => 'Asset Manager', 'description' => 'Asset Manager', 'has_employee_field' => 1, 'created_by' => 1],
            ['id' => 7, 'name' => 'Asset Officer', 'description' => 'Asset Officer', 'has_employee_field' => 1, 'created_by' => 1],
        ]);
    }
}
