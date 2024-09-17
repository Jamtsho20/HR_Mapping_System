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
		    //work structure
			['role_id' => 1, 'system_sub_menu_id' => 17, 'view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1, 'created_by' => 1],
		]);
    }
}
