<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attendance_statuses')->insert([
            [
                'code' => 'P',
                'description' => 'Present'
            ],
            [
                'code' => 'A',
                'description' => 'Absent'
            ],
            [
                'code' => 'H',
                'description' => 'Holiday'
            ],
            [
                'code' => 'E',
                'description' => 'Event'
            ],
            [
                'code' => 'FHDP',
                'description' => 'First Half Day Present'
            ],
            [
                'code' => 'SHDP',
                'description' => 'Second Half Day Present'
            ],
            [
                'code' => 'SHDP',
                'description' => 'Second Half Day Present'
            ],
            [
                'code' => 'CL',
                'description' => 'Casual Leave'
            ],
            [
                'code' => 'FHCL',
                'description' => 'First Halfday Casual Leave'
            ],
            [
                'code' => 'SHCL',
                'description' => 'First Halfday Casual Leave'
            ],
            [
                'code' => 'EL',
                'description' => 'Earned Leave'
            ],
            [
                'code' => 'MedL',
                'description' => 'Medical/Sick Leave'
            ],
            [
                'code' => 'ML',
                'description' => 'Maternity Leave'
            ],
            [
                'code' => 'PL',
                'description' => 'Paternity Leave'
            ],
            [
                'code' => 'EOL',
                'description' => 'Extra Ordinary Leave'
            ],
            [
                'code' => 'SL',
                'description' => 'Study Leave'
            ],
            [
                'code' => 'BL',
                'description' => 'Bereavement Leave'
            ],
            [
                'code' => 'M',
                'description' => 'Meeting'
            ],
            [
                'code' => 'OT',
                'description' => 'On Tour'
            ],
            [
                'code' => 'M',
                'description' => 'Meeting'
            ],
            [
                'code' => 'OT',
                'description' => 'On Tour'
            ],
            [
                'code' => 'TR',
                'description' => 'Training'
            ],
            [
                'code' => 'WO',
                'description' => 'Weekly Off'
            ],
            [
                'code' => 'HDH',
                'description' => 'Half Day Holiday'
            ],
            [
                'code' => 'HDW',
                'description' => 'Half Day Weekend'
            ],
            [
                'code' => 'IL',
                'description' => 'Informed Late'
            ],
        ]);
    }
}
