<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasApprovalHead extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_approval_head')->insert([
            ['id' => 1, 'name' => 'Leave','description' => 'Leave'],
            ['id' => 1, 'name' => 'Expense','description' => 'Expense'],
            ['id' => 1, 'name' => 'Loan/Advance','description' => 'Loan/Advance'],
            ['id' => 1, 'name' => 'Leave Encashment','description' => 'Leave Encashment'],
            ['id' => 1, 'name' => 'Requisition','description' => 'Requisition'],
            ['id' => 1, 'name' => 'Travel Authorization','description' => 'Travel Authorization'],
        ]);
    }
}
