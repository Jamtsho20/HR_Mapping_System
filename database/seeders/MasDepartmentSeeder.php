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
            INSERT INTO `mas_departments` (`short_name`, `name`, `code`, `created_by`) VALUES
            ("HRAD", "Human Resource and Administration Department", 105, 1),
            ("Marketing", "Marketing Department", 108, 1),
            ("FD", "Finance Department", 101, 1),
            ("AND", "Access Network Department",102, 1),
            ("CNCS", "Core Network and Carrier Services Department", 104, 1),
            ("SPPD", "Strategic Planning and Projects Department", 106, 1),
            ("MIS", "Management Information System Department", 103, 1),
            ("IAU", "Internal Audit Unit", 107, 1);
        ');
    }
}
