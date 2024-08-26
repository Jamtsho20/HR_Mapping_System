<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_regions')->insert([
            ['id' => 1, 'name' => 'Gelephu', 'created_by' => 1],
            ['id' => 2, 'name' => 'Phuentsholing', 'created_by' => 1],
            ['id' => 3, 'name' => 'Bumthang', 'created_by' => 1],
            ['id' => 4, 'name' => 'Tsirang', 'created_by' => 1],
            ['id' => 5, 'name' => 'Samdrup Jongkhar', 'created_by' => 1],
            ['id' => 6, 'name' => 'Trashigang', 'created_by' => 1],
            ['id' => 7, 'name' => 'Mongar', 'created_by' => 1],
            ['id' => 8, 'name' => 'Thimphu', 'created_by' => 1],
            ['id' => 9, 'name' => 'Samtse', 'created_by' => 1],
            ['id' => 10, 'name' => 'Paro', 'created_by' => 1],
            ['id' => 11, 'name' => 'Wangdue', 'created_by' => 1],
        ]);
    }
}
