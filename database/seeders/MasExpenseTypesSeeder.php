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
            ['id' => 1, 'name' => 'DSA Advance', 'created_by' => 1],
            ['id' => 2, 'name' => 'Salary Advance', 'created_by' => 1],
            ['id' => 3, 'name' => 'Sifa Loan', 'created_by' => 1],
            ['id' => 4, 'name' => 'Gadget Emi', 'created_by' => 1],
           
        ]);
    }
}
