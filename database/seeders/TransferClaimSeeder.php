<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransferClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('mas_transfer_claims')->insert([
            ['name' => 'Transfer Grant', 'description' => 'Transfer Grant'],
            ['name' => 'Carriage Charge', 'description' => 'Carriage Charge'],
        ]);
    }
}
