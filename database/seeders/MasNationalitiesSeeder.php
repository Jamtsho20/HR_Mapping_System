<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasNationalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_nationalities')->insert([
            ['id' => 1, 'name' => 'Bhutanese', 'created_by' => 1],
            ['id' => 2, 'name' => 'Canadian', 'created_by' => 1],
            ['id' => 3, 'name' => 'Chinese', 'created_by' => 1],
            ['id' => 4, 'name' => 'Indian',  'created_by' => 1],
            ['id' => 5, 'name' => 'Japanese',  'created_by' => 1],
        ]);
    }
}
