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
<<<<<<< HEAD
            ['id' => 1, 'name' => 'Leave', 'description' => 'Leave'],
            ['id' => 2, 'name' => 'Expense', 'description' => 'Expense'],
            ['id' => 3, 'name' => 'Loan/Advance', 'description' => 'Loan/Advance'],
            ['id' => 4, 'name' => 'Leave Encashment', 'description' => 'Leave Encashment'],
            ['id' => 5, 'name' => 'Requisition', 'description' => 'Requisition'],
            ['id' => 6, 'name' => 'Transfer Claim', 'description' => 'Transfer Claim'],
=======
            ['id' => 1, 'name' => 'Leave', 'description' => 'Leave', 'created_by' => 1],
            ['id' => 2, 'name' => 'Expense', 'description' => 'Expense', 'created_by' => 1],
            ['id' => 3, 'name' => 'Loan/Advance', 'description' => 'Loan/Advance', 'created_by' => 1],
            ['id' => 4, 'name' => 'Leave Encashment', 'description' => 'Leave Encashment', 'created_by' => 1],
            ['id' => 5, 'name' => 'Requisition', 'description' => 'Requisition', 'created_by' => 1],        
>>>>>>> cecb407d03a680bca55c62b38f743a02de2fc31c
        ]);
    }
}
