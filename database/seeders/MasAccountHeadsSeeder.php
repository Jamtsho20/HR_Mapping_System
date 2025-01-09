<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class MasAccountHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_acc_account_heads')->insert([
            [
                'code' => 'ALL',
                'name' => 'Allowance',
                'type' => 1, 
                'created_by' => 1, 
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'DED',
                'name' => 'Deduction',
                'type' => 2,
                'created_by' => 1, 
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
