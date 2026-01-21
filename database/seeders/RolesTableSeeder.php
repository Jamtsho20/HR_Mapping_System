<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Administrator', 'description' => 'Super admin', 'created_by' => 1],
            ['id' => 2, 'name' => 'Employee', 'description' => 'Employee', 'created_by' => 1],
            ['id' => 3, 'name' => 'Human Resource', 'description' => 'Human Resource', 'created_by' => 1],
            ['id' => 5, 'name' => 'Head Of Department', 'description' => 'Head of Department', 'created_by' => 1],
        ]);
    }
}
