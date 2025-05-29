<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasOfficeTimingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_office_timings')->insert([
            // ['id' => 1, 'season' => 1, 'start_month' => 'APR', 'end_month' => 'SEP', 'start_time' => '09:00:00', 'lunch_time_from' => '13:00:00', 'lunch_time_to' => '14:00:00', 'end_time' => '17:30:00', 'created_by' => 1],
            ['id' => 1, 'season' => 2, 'start_month' => 'MAR', 'end_month' => 'OCT', 'start_time' => '09:00:00', 'lunch_time_from' => '13:00:00', 'lunch_time_to' => '14:00:00', 'end_time' => '17:00:00'],
            // ['id' => 1, 'season' => 3, 'start_month' => 'APR', 'end_month' => 'SEP', 'start_time' => '09:00:00', 'lunch_time_from' => '13:00:00', 'lunch_time_to' => '14:00:00', 'end_time' => '17:30:00', 'created_by' => 1],
            ['id' => 2, 'season' => 4, 'start_month' => 'NOV', 'end_month' => 'FEB', 'start_time' => '09:00:00', 'lunch_time_from' => '13:00:00', 'lunch_time_to' => '14:00:00', 'end_time' => '17:00:00']
        ]);
    }
}
