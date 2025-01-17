<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budgetCodes = [
            [
                'code' => '31111',
                'particular' => 'Network Systems',
                'budget_type_id' => 1, // Capital
                'created_by' => 1,
            ],
            [
                'code' => '31261',
                'particular' => 'ISP Systems',
                'budget_type_id' => 1, // Capital
                'created_by' => 1,
            ],
            [
                'code' => '31311',
                'particular' => 'Tools & Equipment',
                'budget_type_id' => 1, // Capital
                'created_by' => 1,

            ],
            [
                'code' => '31711',
                'particular' => 'Office Equipment',
                'budget_type_id' => 1, // Capital
                'created_by' => 1,
            ],
            [
                'code' => '31756',
                'particular' => 'Furniture & Fixture',
                'budget_type_id' => 1, // Capital
                'created_by' => 1,
            ],
            [
                'code' => '31956',
                'particular' => 'Power Utilities & AC Equip.',
                'budget_type_id' => 1, // Capital
                'created_by' => 1,
            ],
          
            [
                'code' => '52144',
                'particular' => 'Electricity & Power Expense',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '52171',
                'particular' => 'Fees & Subscriptions',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '52180',
                'particular' => 'Generator POL',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '52181',
                'particular' => 'Transportation Charges',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '52192',
                'particular' => 'Vehicle Hire charges',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '52195',
                'particular' => 'Hire charges (Others)',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '52212',
                'particular' => 'Annual Maintenance Contract',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '53111',
                'particular' => 'Rental Payment',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
            [
                'code' => '53711',
                'particular' => 'Repair & Maintenance',
                'budget_type_id' => 2, // Current
                'created_by' => 1,

            ],
            [
                'code' => '54112',
                'particular' => 'Repair & Maintenance - Network',
                'budget_type_id' => 2, // Current
                'created_by' => 1,

            ],
            [
                'code' => '54120',
                'particular' => 'Repair & Maintenance - Tower Eq',
                'budget_type_id' => 2, // Current
                'created_by' => 1,

            ],
            [
                'code' => '54121',
                'particular' => 'Repair & Maintenance - Shelter',
                'budget_type_id' => 2, // Current
                'created_by' => 1,

            ],
            [
                'code' => '54126',
                'particular' => 'Repair & Maintenance - Power System',
                'budget_type_id' => 2, // Current
                'created_by' => 1,

            ],
            [
                'code' => '54529',
                'particular' => 'Entertainment Expenses',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
                
            ],
            [
                'code' => '54542',
                'particular' => 'Consumption of Stores',
                'budget_type_id' => 2, // Current
                'created_by' => 1,

            ],
            [
                'code' => '55511',
                'particular' => 'Travel In-country',
                'budget_type_id' => 2, // Current
                'created_by' => 1,
            ],
        ];

        // Insert budget codes into the database
        foreach ($budgetCodes as $budgetCode) {
            DB::table('budget_codes')->insert([
                'code' => $budgetCode['code'],
                'particular' => $budgetCode['particular'],
                'budget_type_id' => $budgetCode['budget_type_id'],
                'created_by' => $budgetCode['created_by'],
            ]);
        }
    }
}
