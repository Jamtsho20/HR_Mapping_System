<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasAttendanceFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_attendance_features')->insert([
            ['id' => 1, 'name' => 'Checkin', 'is_mandatory' => 1, 'status' => 1, 'created_by' => 1],
            ['id' => 2, 'name' => 'Checkout', 'is_mandatory' => 1, 'status' => 1, 'created_by' => 1],
            ['id' => 3, 'name' => 'Geofencing', 'is_mandatory' => 1, 'status' => 1, 'created_by' => 1],
            ['id' => 4, 'name' => 'Submission/Verification', 'is_mandatory' => 1, 'status' => 1, 'created_by' => 1],
            ['id' => 6, 'name' => 'QR Code Scan', 'is_mandatory' => 1, 'status' => 0, 'created_by' => 1],
            // ['id' => 7, 'name' => 'Attendance Compilation Method', 'is_mandatory' => 1, 'status' => 0, 'created_by' => 1],
        ]);
    }
}
