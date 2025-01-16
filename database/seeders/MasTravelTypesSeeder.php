<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasTravelTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $travelTypes = [
            [
                'name' => 'In Country',
                'code' => 'IC_TA',
                'status' => 1,
                'remarks' => 'For In Country purposes',
                'created_by' => 1,
                'updated_by' => null,
            ],
            [
                'name' => 'India',
                'code' => 'I_TA',
                'status' => 1,
                'remarks' => 'For India purposes',
                'created_by' => 1,
                'updated_by' => null,
            ],
            [
                'name' => 'Third Country',
                'code' => 'TC_TA',
                'status' => 1,
                'remarks' => 'For Third Country purposes',
                'created_by' => 1,
                'updated_by' => null,
            ],
        ];

        DB::table('mas_travel_types')->insert($travelTypes);
    }
}
