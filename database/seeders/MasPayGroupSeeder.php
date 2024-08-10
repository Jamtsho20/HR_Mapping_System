<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class MasPayGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_pay_groups')->insert([
            [
                'name' => 'Grade Wise SIFA',
                'applicable_on' => 2, // Employee Group
                'created_by' => 1, // Replace with actual UUID
                'edited_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grade Wise Communication Allowance',
                'applicable_on' => 2, 
                'created_by' => 1,
                'edited_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Critical Staff Group',
                'applicable_on' => 1, 
                'created_by' => 1, 
                'edited_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    } 
}

