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
            ['id' => 1, 'expense_type' => 'DSA Advance', 'created_by' => 1],
            ['id' => 2, 'expense_type' => 'Salary Advance', 'created_by' => 1],
            ['id' => 3, 'expense_type' => 'Sifa Loan', 'created_by' => 1],
            ['id' => 4, 'expense_type' => 'Gadget Emi', 'created_by' => 1],
           
        ]);
    }
}
