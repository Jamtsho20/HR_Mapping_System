<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HierarchyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
            INSERT INTO `system_hierarchies` (`id`, `name`, `created_by`) VALUES
            (1, "Asset Commission", 1),
            (2, "Asset Requisition", 1),
            (3, "Asset Transfer", 1),
            (4, "Bereavement", 1),
            (5, "Casual leave", 1),
            (6, "DSA Advance", 1),
            (7, "DSA settlement", 1),
            (8, "Earned Leave", 1),
            (9, "Fixed Asset Return", 1),
            (10, "Maternity", 1),
            (11, "Medical", 1),
            (12, "Paternity", 1),
            (13, "Salary Advance", 1),
            (14jhjuh, "SamsungEmi", 1)
        ');
    }
}
