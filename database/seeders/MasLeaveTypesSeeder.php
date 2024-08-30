<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasLeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_leave_types')->insert([
            ['name' => 'Casual Leave', 'applicable_to' => 2, 'max_days' => 10, 'remarks' => 'Available for both regular and probation employees', 'created_by' => 1],
            ['name' => 'Earned Leave', 'applicable_to' => 1, 'max_days' => 30, 'remarks' => 'Can be availed by regular employee after exceeding 30 days', 'created_by' => 1],
            ['name' => 'Medical / Sick Leave', 'applicable_to' => 2, 'max_days' => 30, 'remarks' => 'Can be availed by employee upon providing medical certificate', 'created_by' => 1],
            ['name' => 'Maternity Leave', 'applicable_to' => 2, 'max_days' => 90, 'remarks' => 'Applicable only to female employees', 'created_by' => 1],
            ['name' => 'Paternity Leave', 'applicable_to' => 1, 'max_days' => 5, 'remarks' => 'Applicable only to male employees', 'created_by' => 1],
            ['name' => 'Extra Oridinary Leave', 'applicable_to' => 1, 'max_days' => 0, 'remarks' => 'Can be availed only after completion of two years of service in the company', 'created_by' => 1],
            ['name' => 'Study Leave', 'applicable_to' => 1, 'max_days' => 730, 'remarks' => 'Applicable for regular employees pursuing further studies', 'created_by' => 1],
            ['name' => 'Bereavement Leave', 'applicable_to' => 2, 'max_days' => 14, 'remarks' => 'Applicable for employees incase of death of a dependent family member ', 'created_by' => 1],
        ]);
    }
}
