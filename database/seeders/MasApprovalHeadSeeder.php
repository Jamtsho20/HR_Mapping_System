<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasApprovalHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_approval_heads')->insert([
            ['id' => 1, 'name' => 'Leave', 'description' => 'Leave','updated_by' => null, 'created_by' => 1],
            ['id' => 2, 'name' => 'Expense', 'description' => 'Expense', 'updated_by' => null, 'created_by' => 1 ],
            ['id' => 3, 'name' => 'Loan/Advance', 'description' => 'Loan/Advance', 'updated_by' => null, 'created_by' => 1 ],
            ['id' => 4, 'name' => 'Leave Encashment', 'description' => 'Leave Encashment', 'updated_by' => null, 'created_by' => 1],
            ['id' => 5, 'name' => 'Requisition', 'description' => 'Requisition', 'updated_by' => null, 'created_by' => 1],        
        ]);
    }
}
