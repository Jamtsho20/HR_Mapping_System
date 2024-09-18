<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('mas_employee_roles')->insert([
            'id' => 1,
            'mas_employee_id' => 1,
            'role_id' => 1,
            'created_by' => 1
        ]);
    }
}
