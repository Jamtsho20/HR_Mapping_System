<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasRegionLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_region_locations')->insert([
            ['id' => 1, 'mas_region_id' => 1, 'name' => 'Customer Care Center, Bumthang', 'mas_dzongkhag_id' => 1, 'created_by' => 1],
            ['id' => 2, 'mas_region_id' => 1, 'name' => 'Extension Counter, Trongsa', 'mas_dzongkhag_id' => 1, 'created_by' => 1],
            ['id' => 3, 'mas_region_id' => 1, 'name' => 'Extension Counter, Zhemgang', 'mas_dzongkhag_id' => 1, 'created_by' => 1],

            ['id' => 4, 'mas_region_id' => 2, 'name' => 'Customer Care Center, Phuentsholing', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 5, 'mas_region_id' => 2, 'name' => 'Extension Counter, Tshimasham', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 6, 'mas_region_id' => 2, 'name' => 'Extension Counter, Gedu ', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 7, 'mas_region_id' => 2, 'name' => 'Extension Counter, Kalikhola', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 8, 'mas_region_id' => 2, 'name' => 'Extension Counter, Samtse', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 9, 'mas_region_id' => 2, 'name' => 'Extension Counter, Gomtu', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 10, 'mas_region_id' => 2, 'name' => 'Extension Counter, Sipsu', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 11, 'mas_region_id' => 2, 'name' => 'Extension Counter, Dorokha', 'mas_dzongkhag_id' => 2, 'created_by' => 1],

            ['id' => 12, 'mas_region_id' => 3, 'name' => 'Customer Care Center, Gelephu', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 13, 'mas_region_id' => 3, 'name' => 'Extension Counter, Tsirang', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 14, 'mas_region_id' => 3, 'name' => 'Extension Counter, Dagapela', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 15, 'mas_region_id' => 3, 'name' => 'NetOps Building, Gelephu', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 16, 'mas_region_id' => 4, 'name' => 'Customer Care Center, Mongar', 'mas_dzongkhag_id' => 7, 'created_by' => 1],
            ['id' => 17, 'mas_region_id' => 4, 'name' => 'Extension Counter, Dagapela', 'mas_dzongkhag_id' => 7, 'created_by' => 1],
            ['id' => 18, 'mas_region_id' => 5, 'name' => 'Customer Care Center, Paro', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 19, 'mas_region_id' => 5, 'name' => 'Extension Counter, Paro Airport', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 20, 'mas_region_id' => 5, 'name' => 'Extension Counter, Haa', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 21, 'mas_region_id' => 6, 'name' => 'Customer Care Center, Samdrup Jongkhar', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 22, 'mas_region_id' => 6, 'name' => 'Extension Counter, Bangter', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 23, 'mas_region_id' => 6, 'name' => 'Extension Counter, Daifam', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 24, 'mas_region_id' => 6, 'name' => 'Extension Counter, Pemagatshel', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 25, 'mas_region_id' => 6, 'name' => 'Nanglam Extension Office', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
           
            ['id' => 26, 'mas_region_id' => 7, 'name' => 'Customer Care Center, Thimphu', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 27, 'mas_region_id' => 7, 'name' => 'NetOps Building, Thimphu', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 28, 'mas_region_id' => 8, 'name' => 'Head Office', 'mas_dzongkhag_id' => 15, 'created_by' => 1],

            ['id' => 29, 'mas_region_id' => 8, 'name' => 'Customer Care Center, Trashigang', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 30, 'mas_region_id' => 8, 'name' => 'Extension Counter, Wamwrong', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 31, 'mas_region_id' => 8, 'name' => 'Extension Counter, Trashiyangtse', 'mas_dzongkhag_id' => 15, 'created_by' => 1],

            ['id' => 32, 'mas_region_id' => 9, 'name' => 'Customer Care Center, Wangdue Phodrang', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
            ['id' => 33, 'mas_region_id' => 9, 'name' => 'Extension Counter, Gangtey', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
            ['id' => 34, 'mas_region_id' => 9, 'name' => 'Extension Counter, Punakha', 'mas_dzongkhag_id' => 19, 'created_by' => 1],


            // ['id' => 3, 'mas_region_id' => 2, 'name' => 'Phuentsholing', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            // ['id' => 4, 'mas_region_id' => 2, 'name' => 'Chukha', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            // ['id' => 5, 'mas_region_id' => 3, 'name' => 'Bumthang', 'mas_dzongkhag_id' => 1, 'created_by' => 1],
            // ['id' => 6, 'mas_region_id' => 3, 'name' => 'Trongsa', 'mas_dzongkhag_id' => 17, 'created_by' => 1],
            // ['id' => 7, 'mas_region_id' => 1, 'name' => 'Tsirang', 'mas_dzongkhag_id' => 18, 'created_by' => 1],
            // ['id' => 8, 'mas_region_id' => 1, 'name' => 'Dagana', 'mas_dzongkhag_id' => 3, 'created_by' => 1],
            // ['id' => 9, 'mas_region_id' => 5, 'name' => 'Samdrup Jongkhar', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            // ['id' => 10, 'mas_region_id' => 5, 'name' => 'Pema Gatshel', 'mas_dzongkhag_id' => 9, 'created_by' => 1],
            // ['id' => 11, 'mas_region_id' => 6, 'name' => 'Trashigang', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            // ['id' => 12, 'mas_region_id' => 6, 'name' => 'Trashi Yangtse', 'mas_dzongkhag_id' => 16, 'created_by' => 1],
            // ['id' => 13, 'mas_region_id' => 7, 'name' => 'Mongar', 'mas_dzongkhag_id' => 7, 'created_by' => 1],
            // ['id' => 14, 'mas_region_id' => 7, 'name' => 'Lhuntse', 'mas_dzongkhag_id' => 6, 'created_by' => 1],
            // ['id' => 15, 'mas_region_id' => 8, 'name' => 'Thimphu', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            // ['id' => 16, 'mas_region_id' => 2, 'name' => 'Samtse', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            // ['id' => 17, 'mas_region_id' => 10, 'name' => 'Paro', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            // ['id' => 18, 'mas_region_id' => 10, 'name' => 'Haa', 'mas_dzongkhag_id' => 5, 'created_by' => 1],
            // ['id' => 19, 'mas_region_id' => 11, 'name' => 'Wangdue', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
            // ['id' => 20, 'mas_region_id' => 11, 'name' => 'Gasa', 'mas_dzongkhag_id' => 4, 'created_by' => 1],
            // ['id' => 21, 'mas_region_id' => 11, 'name' => 'Punakha', 'mas_dzongkhag_id' => 10, 'created_by' => 1],
        ]);
    }
}
