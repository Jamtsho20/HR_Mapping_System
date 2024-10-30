<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('mas_vehicles')->insert([
            ['id' => 1, 'name' => 'Bolero', 'vihicle_no' => 'BP-1-B3126', 'vehicle_type' => 2, 'created_by' => 1],
            ['id' => 2, 'name' => 'Van', 'vihicle_no' => 'BP-1-B2146', 'vehicle_type' => 1, 'created_by' => 1],
            ['id' => 3, 'name' => 'Creta', 'vihicle_no' => 'BP-1-B5553', 'vehicle_type' => 1, 'created_by' => 1],
            ['id' => 4, 'name' => 'Santafee', 'vihicle_no' => 'BP-1-B3796', 'vehicle_type' => 1, 'created_by' => 1],
            ['id' => 5, 'name' => 'Motor Bike', 'vihicle_no' => 'BP-1-B3777', 'vehicle_type' => 4, 'created_by' => 1],
        ]);
    }
}
