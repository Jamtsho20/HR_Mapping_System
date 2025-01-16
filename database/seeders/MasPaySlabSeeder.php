<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MasPaySlabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_pay_slabs')->insert([
            [
                'name' => 'TDS',
                'effective_date' => '2024-01-01',
                'formula' => 'basic_salary * 0.5',
                'created_by' => 1,
                'edited_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GSLI',
                'effective_date' => '2024-01-01',
                'formula' => 'basic_salary * 0.2',
                'created_by' => 1,
                'edited_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    
}

