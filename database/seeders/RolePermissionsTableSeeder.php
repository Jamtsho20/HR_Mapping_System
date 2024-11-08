<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('role_permissions')->insert([
			//system settings sub menu
			['role_id' => 1, 'system_sub_menu_id' => 1, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 2, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 3, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//master sub menu
			['role_id' => 1, 'system_sub_menu_id' => 4, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 5, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 6, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 7, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 8, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 9, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 10, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 11, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 12, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 13, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 14, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 15, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 16, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 17, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 18, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 19, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 20, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 21, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 22, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 23, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 24, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 25, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//work structure
			['role_id' => 1, 'system_sub_menu_id' => 26, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 27, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 28, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Pay Master
			['role_id' => 1, 'system_sub_menu_id' => 29, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 30, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 31, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 32, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Expense
			['role_id' => 1, 'system_sub_menu_id' => 33, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 34, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 35, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 36, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 37, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 38, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 39, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 40, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 41, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Leave
			['role_id' => 1, 'system_sub_menu_id' => 42, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 43, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 44, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 45, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 46, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 47, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Advance/Loan
			['role_id' => 1, 'system_sub_menu_id' => 48, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 49, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 50, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Attendance
			['role_id' => 1, 'system_sub_menu_id' => 51, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 52, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 53, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Delegaton Approval
			['role_id' => 1, 'system_sub_menu_id' => 54, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 55, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 56, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 57, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 58, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 59, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 60, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//EMployee
			['role_id' => 1, 'system_sub_menu_id' => 61, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//SIFA
			['role_id' => 1, 'system_sub_menu_id' => 62, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Asset
			['role_id' => 1, 'system_sub_menu_id' => 63, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 64, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 65, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 66, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 67, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 68, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 69, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 70, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 71, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 72, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 73, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 74, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 75, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 76, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 77, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 78, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 79, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//PayRoll
			['role_id' => 1, 'system_sub_menu_id' => 80, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 81, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 82, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 83, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],

			//Report
			['role_id' => 1, 'system_sub_menu_id' => 84, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 85, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 86, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 87, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 88, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 89, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 90, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
			['role_id' => 1, 'system_sub_menu_id' => 91, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],


		]);
	}
}
