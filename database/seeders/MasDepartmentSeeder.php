<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
            INSERT INTO `mas_departments` (`short_name`, `name`, `created_by`) VALUES
            ("HRAD", "Human Resource and Administration Department", 1),
            ("Marketing", "Marketing Department", 1),
            ("Finance", "Finance Department", 1),
            ("AND", "Access Network Department", 1),
            ("CNCS", "Core Network and Carrier Services Department", 1),
            ("SPPD", "Strategic Planning and Projects Departmen", 1),
            ("MIS", "Management Information System Department", 1),
            ("IAU", "Internal Audit Unit", 1);
        ');
    }
}
