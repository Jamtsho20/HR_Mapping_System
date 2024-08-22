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
            ['id' => 1, 'name' => 'Gelephu', 'rm_email' => 'rm.gelephu@tashicell.com', 'rm_phone' => '77100280', 'created_by' => 1],
            ['id' => 2, 'name' => 'Phuentsholing', 'rm_email' => 'rm.pling@tashicell.com', 'rm_phone' => '77763682', 'created_by' => 1],
            ['id' => 3, 'name' => 'Bumthang', 'rm_email' => 'rm.bumthang@tashicell.com', 'rm_phone' => '77352911', 'created_by' => 1],
            ['id' => 4, 'name' => 'Tsirang', 'rm_email' => 'rm.tsirang@tashicell.com', 'rm_phone' => '77186060', 'created_by' => 1],
            ['id' => 5, 'name' => 'Samdrup Jongkhar', 'rm_email' => 'rm.sjk@tashicell.com', 'rm_phone' => '77109494', 'created_by' => 1],
            ['id' => 6, 'name' => 'Trashigang', 'rm_email' => 'rm.trashigang@tashicell.com', 'rm_phone' => '77113953', 'created_by' => 1],
            ['id' => 7, 'name' => 'Mongar', 'rm_email' => 'rm.mongar@tashicell.com', 'rm_phone' => '77900700', 'created_by' => 1],
            ['id' => 8, 'name' => 'Thimphu', 'rm_email' => 'rm.thimphu@tashicell.com', 'rm_phone' => '77108822', 'created_by' => 1],
            ['id' => 9, 'name' => 'Samtse', 'rm_email' => 'rm.samtse@tashicell.com', 'rm_phone' => '77260064', 'created_by' => 1],
            ['id' => 10, 'name' => 'Paro', 'rm_email' => 'rm.paro@tashicell.com', 'rm_phone' => '77101666', 'created_by' => 1],
            ['id' => 11, 'name' => 'Wangdue', 'rm_email' => 'rm.wangdue@tashicell.com', 'rm_phone' => '77101918', 'created_by' => 1],
        ]);
    }
}
