<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasOfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_offices')->insert([
            ['id' => 1, 'name' => 'Customer Care Center, Bumthang', 'mas_dzongkhag_id' => 1, 'created_by' => 1],
            ['id' => 2, 'name' => 'Extension Counter, Trongsa', 'mas_dzongkhag_id' => 1, 'created_by' => 1],
            ['id' => 3, 'name' => 'Extension Counter, Zhemgang', 'mas_dzongkhag_id' => 1, 'created_by' => 1],

            ['id' => 4, 'name' => 'Customer Care Center, Phuentsholing', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 5, 'name' => 'Extension Counter, Tshimasham', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 6, 'name' => 'Extension Counter, Gedu ', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 7, 'name' => 'Extension Counter, Kalikhola', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 8, 'name' => 'Extension Counter, Samtse', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 9, 'name' => 'Extension Counter, Gomtu', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 10, 'name' => 'Extension Counter, Sipsu', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 11, 'name' => 'Extension Counter, Dorokha', 'mas_dzongkhag_id' => 2, 'created_by' => 1],

            ['id' => 12, 'name' => 'Customer Care Center, Gelephu', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 13, 'name' => 'Extension Counter, Tsirang', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 14, 'name' => 'Extension Counter, Dagapela', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 15, 'name' => 'NetOps Building, Gelephu', 'mas_dzongkhag_id' => 13, 'created_by' => 1],

            ['id' => 16, 'name' => 'Customer Care Center, Mongar', 'mas_dzongkhag_id' => 7, 'created_by' => 1],
            ['id' => 17, 'name' => 'Extension Counter, Dagapela', 'mas_dzongkhag_id' => 7, 'created_by' => 1],

            ['id' => 18, 'name' => 'Customer Care Center, Paro', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 19, 'name' => 'Extension Counter, Paro Airport', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 20, 'name' => 'Extension Counter, Haa', 'mas_dzongkhag_id' => 8, 'created_by' => 1],

            ['id' => 21, 'name' => 'Customer Care Center, Samdrup Jongkhar', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 22, 'name' => 'Extension Counter, Bangter', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 23, 'name' => 'Extension Counter, Daifam', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 24, 'name' => 'Extension Counter, Pemagatshel', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 25, 'name' => 'Nanglam Extension Office', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
           
            ['id' => 26, 'name' => 'Customer Care Center, Thimphu', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 27, 'name' => 'NetOps Building, Thimphu', 'mas_dzongkhag_id' => 14, 'created_by' => 1],

            ['id' => 28, 'name' => 'Head Office', 'mas_dzongkhag_id' => 15, 'created_by' => 1],

            ['id' => 29, 'name' => 'Customer Care Center, Trashigang', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 30, 'name' => 'Extension Counter, Wamwrong', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 31, 'name' => 'Extension Counter, Trashiyangtse', 'mas_dzongkhag_id' => 15, 'created_by' => 1],

            ['id' => 32, 'name' => 'Customer Care Center, Wangdue Phodrang', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
            ['id' => 33, 'name' => 'Extension Counter, Gangtey', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
            ['id' => 34, 'name' => 'Extension Counter, Punakha', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
        ]);
    }
}
