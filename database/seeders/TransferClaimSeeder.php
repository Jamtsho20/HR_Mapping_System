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
            ['id' => 1, 'name' => 'Transfer Grant', 'code' => '52349', 'description' => 'Transfer Grant'],
            ['id' => 2, 'name' => 'Carriage Charge', 'code' => '52345', 'description' => 'Carriage Charge'],
        ]);
    }
}
