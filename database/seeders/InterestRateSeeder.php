<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetching advance types
        $advanceTypes = DB::table('mas_advance_types')->get();

        $interestRates = [
          
            'Advance to Staff' => null, 
            'DSA Advance(Tour)' => null, 
            'Electricity Imprest Advance' => null, 
            'Gadget EMI' => null, 
            'Imprest Advance' => null, 
            'Salary Advance' => null, 
            'SIFA LOAN' => 15.00, 
        ];

        foreach ($advanceTypes as $type) {
            DB::table('interest_rates')->insert([
                'advance_type_id' => $type->id,
                'rate' => $interestRates[$type->name] ?? null, 
                'status' => 1,
                'created_by' => 1, 
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
