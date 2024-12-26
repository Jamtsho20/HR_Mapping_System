<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasVehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('mas_vehicle_types')->insert([
            ['name' => 'Bike', 'mileage' => 10],
            ['name' => 'CoW', 'mileage' => 10],
            ['name' => 'Hyundai Creta', 'mileage' => 10],
            ['name' => 'Hyundai Gr4i10 Nios', 'mileage' => 10],
            ['name' => 'Hyundai i20 Active', 'mileage' => 10],
            ['name' => 'Hyundai Santro', 'mileage' => 10],
            ['name' => 'Isuzu D/Max (Double Cabin)', 'mileage' => 10],
            ['name' => 'Isuzu D/Max (Single Cabin)', 'mileage' => 10],
            ['name' => 'Isuzu MUX', 'mileage' => 10],
            ['name' => 'Mahindra Bolero (Double Cabin)', 'mileage' => 10],
            ['name' => 'Mahindra Bolero (Single Cabin)', 'mileage' => 10],
            ['name' => 'Maruti EECO', 'mileage' => 10],
            ['name' => 'Maruti WagonR', 'mileage' => 10],
        ]);
    }
}
