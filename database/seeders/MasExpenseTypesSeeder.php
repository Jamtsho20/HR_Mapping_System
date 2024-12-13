<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasExpenseTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_expense_types')->insert([
            ['id' => 1,'type_id' => null, 'name' => 'Conveyance Expsense', 'code' => 'CE', 'created_by' => 1],
            ['id' => 2,'type_id' => null, 'name' => 'General Expsense', 'code' => 'GE', 'created_by' => 1],
            ['id' => 3, 'type_id' => 1, 'name' => 'DSA Claim/Settlement', 'code' => '55511', 'created_by' => 1,],
            ['id' => 4, 'type_id' => 1, 'name' => 'Transfer Claim', 'code' => 'TC', 'created_by' => 1],
            ['id' => 5, 'type_id' => 2, 'name' => 'Vehicle Fuel Claim', 'code' => '31811', 'created_by' => 1],
            ['id' => 6, 'type_id' => 2, 'name' => 'Parking Fee', 'code' => '52171', 'created_by' => 1],
        ]);
    }
}
