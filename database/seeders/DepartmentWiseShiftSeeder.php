<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentWiseShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('department_wise_shifts')->insert([
            // AND Dept
            ['id' => 1, 'name' => 'AND - ISP Help Desk Morning Shift','type_id' => 1, 'department_id' => 4, 'start_time' => '08:30:00', 'end_time' => '16:00:00', 'status' => 1, 'created_by' => 1],
            ['id' => 2, 'name' => 'AND - ISP Help Desk Evening Shift', 'type_id' => 2, 'department_id' => 4, 'start_time' => '13:00:00','end_time' => '21:00:00', 'status' => 1, 'created_by' => 1],

            // HRAD Dept
            ['id' => 3, 'name' => 'HRAD – Security Guard Morning Shift', 'type_id' => 1, 'department_id' => 1, 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'status' => 1, 'created_by' => 1],
            ['id' => 4, 'name' => 'HRAD – Security Guard Night Shift', 'type_id' => 3, 'department_id' => 1, 'start_time' => '17:00:00','end_time' => '07:30:00', 'status' => 1, 'created_by' => 1],

            // CNCS Dept
            ['id' => 5, 'name' => 'CNCS Department – OMC Morning Shift', 'type_id' => 1, 'department_id' => 5, 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'status' => 1, 'created_by' => 1],
            ['id' => 6, 'name' => 'CNCS Department – OMC Evening Shift', 'type_id' => 2, 'department_id' => 5, 'start_time' => '17:00:00','end_time' => '07:30:00', 'status' => 1, 'created_by' => 1],
            ['id' => 7, 'name' => 'CNCS Department – OMC Night Shift', 'type_id' => 3, 'department_id' => 5, 'start_time' => '17:00:00','end_time' => '07:30:00', 'status' => 1, 'created_by' => 1],

            // MIS Dept
            ['id' => 8, 'name' => 'MIS Department – CC Morning Shift', 'type_id' => 1, 'department_id' => 7, 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'status' => 1, 'created_by' => 1],
            ['id' => 9, 'name' => 'MIS Department – CC Evening Shift', 'type_id' => 2, 'department_id' => 7, 'start_time' => '17:00:00','end_time' => '07:30:00', 'status' => 1, 'created_by' => 1],
        ]);
    }
}
