<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemMenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_menus')->insert([
            ['id' => 1, 'name' => 'System Setting', 'icon' => 'fa-cogs', 'display_order' => 1],
            ['id' => 2, 'name' => 'Master', 'icon' => 'fa-list', 'display_order' => 2],
            ['id' => 3, 'name' => 'Work Structure', 'icon' => 'fa-calendar', 'display_order' => 3],
            ['id' => 4, 'name' => 'Pay Master', 'icon' => 'fa-money', 'display_order' => 4],
            ['id' => 5, 'name' => 'Expense', 'icon' => 'fa-money', 'display_order' => 5],
            ['id' => 6, 'name' => 'Leave', 'icon' => 'fa-calendar', 'display_order' => 6],
            ['id' => 7, 'name' => 'Advance/Loan', 'icon' => 'fa-money', 'display_order' => 7],
            ['id' => 8, 'name' => 'Attendance', 'icon' => 'fa-pencil-square-o', 'display_order' => 8],
            ['id' => 9, 'name' => 'Delegation Approval', 'icon' => 'fa-pencil-square-o', 'display_order' => 9],
            ['id' => 10, 'name' => 'Employee', 'icon' => 'fa-users', 'display_order' => 10],
            ['id' => 11, 'name' => 'Sifa', 'icon' => 'fa-user', 'display_order' => 11],
            ['id' => 12, 'name' => 'Reports', 'icon' => 'fa-flag', 'display_order' => 12],
            ['id' => 13, 'name' => 'Asset', 'icon' => 'fa-pie-chart', 'display_order' => 13],
            ['id' => 14, 'name' => 'Payroll', 'icon' => 'fa-money', 'display_order' => 14],
            ['id' => 15, 'name' => 'LTC', 'icon' => 'fa-money', 'display_order' => 15],
            ['id' => 16, 'name' => 'Report', 'icon' => 'fa-file', 'display_order' => 16],
            ['id' => 17, 'name' => 'Travel Authorization', 'icon' => 'fa-plane', 'display_order' => 17],
            ['id' => 18, 'name' => 'Approval', 'icon' => 'fa-check', 'display_order' => 18],
            ['id' => 19, 'name' => 'Team', 'icon' => 'fa-users', 'display_order' => 19],
            ['id' => 20, 'name' => 'Asset Report', 'icon' => 'fa-users', 'display_order' => 20],
            ['id' => 21, 'name' => 'My Profile', 'icon' => 'fa-users', 'display_order' => 21],
            ['id' => 21, 'name' => 'Retirement Benefit Nomination', 'icon' => 'fa-users', 'display_order' => 22],
        ]);
    }
}
