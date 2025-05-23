<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'code' => 'L',
                'description' => 'Leave'
            ],
            [
                'code' => 'SHDP',
                'description' => 'Second Half Day Present'
            ],
            [
                'code' => 'L',
                'description' => 'Leave'
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
