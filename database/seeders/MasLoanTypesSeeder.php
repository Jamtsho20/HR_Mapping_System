<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasLoanTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_loan_types')->insert([
            ['id' => 1, 'name' => 'Consumer Loan', 'code' => '', 'created_by' => 1],
            ['id' => 2, 'name' => 'Employee Loan', 'code' => '', 'created_by' => 1],
            ['id' => 3, 'name' => 'Festival Loan', 'code' => '', 'created_by' => 1],
            ['id' => 4, 'name' => 'Gadget Loan', 'code' => '', 'created_by' => 1],
            ['id' => 5, 'name' => 'Housing Loan', 'code' => '', 'created_by' => 1],
            ['id' => 6, 'name' => 'Personal Loan', 'code' => '', 'created_by' => 1],
            ['id' => 7, 'name' => 'PPF Loan', 'code' => '', 'created_by' => 1],
            ['id' => 8, 'name' => 'Vehicle Loan', 'code' => '', 'created_by' => 1],

        ]);
    }
}
