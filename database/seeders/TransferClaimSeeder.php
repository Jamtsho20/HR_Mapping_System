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
            ['id' => 1, 'name' => 'Transfer Grant', 'description' => 'Transfer Grant'],
            ['id' => 2, 'name' => 'Carriage Charge', 'description' => 'Carriage Charge'],
        ]);
    }
}
