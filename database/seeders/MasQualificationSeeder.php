<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_qualifications')->insert([
            ['id' => 1, 'name' => 'Grade VIII', 'created_by' => 1],
            ['id' => 2, 'name' => 'Grade X', 'created_by' => 1],
            ['id' => 3, 'name' => 'Grade XII', 'created_by' => 1],
            ['id' => 4, 'name' => 'Diploma Level', 'created_by' => 1],
            ['id' => 5, 'name' => 'Bachelors Degree', 'created_by' => 1],
            ['id' => 6, 'name' => 'Masters Degree', 'created_by' => 1],
            ['id' => 7, 'name' => 'PhD Degree', 'created_by' => 1],
        ]);
    }
}
