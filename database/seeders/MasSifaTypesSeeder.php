<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasSifaTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sifaTypes = [
            [
                'name' => 'Sifa',
                'code' => 'Sifa',
                'remarks' => 'Staff Initiative FOr Financial Assistance ',
                'created_by' => 1, 
                'updated_by' => null,
            ],

        ];

        DB::table('mas_sifa_types')->insert($sifaTypes);
    }
}
