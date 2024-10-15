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
            ['id' => 1, 'name' => 'Conveyance', 'created_by' => 1],
            ['id' => 2, 'name' => 'General', 'created_by' => 1],
            ['id' => 3, 'mas_expense_type_id' => 1, 'name' => 'DSA Claim', 'created_by' => 1,],
            ['id' => 4, 'mas_expense_type_id' => 1, 'name' => 'Transfer Claim', 'created_by' => 1],
            ['id' => 5, 'mas_expense_type_id' => 2, 'name' => 'Vehicle Ful Calim', 'created_by' => 1],
            ['id' => 6, 'mas_expense_type_id' => 2, 'name' => 'Leave encashment', 'created_by' => 1],
            ['id' => 7, 'mas_expense_type_id' => 2, 'name' => 'DEG Fuel Claim', 'created_by' => 1],
            ['id' => 8, 'mas_expense_type_id' => 2, 'name' => 'Parking Fee', 'created_by' => 1],

        ]);
    }
}
