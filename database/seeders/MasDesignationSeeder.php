<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
            INSERT INTO `mas_designations` (`name`, `created_by`) VALUES
            ("Access Network Engineer", 1),
            ("Accounts Officer", 1),
            ("Administrative Officer", 1),
            ("Asset Officer", 1),
            ("Assistant Revenue Officer", 1),
            ("Bill Collector", 1),
            ("Billing Engineer", 1),
            ("Contact Center Engineer", 1),
            ("Contact Center Executive", 1),
            ("Core Network Engineer", 1),
            ("Customer Care Executive", 1),
            ("Driver", 1),
            ("ERP Engineer", 1),
            ("Executive Manager", 1),
            ("General Manager", 1),
            ("Graphic Designer", 1),
            ("Human Resource Management Officer", 1),
            ("Human Resource Development Officer", 1),
            ("Human Resource Officer", 1),
            ("Internal Auditor", 1),
            ("Inventory Officer", 1),
            ("Manager", 1),
            ("Marketing Officer", 1),
            ("MFS Engineer", 1),
            ("Optimization Engineer", 1),
            ("PR and Administrative Officer", 1),
            ("Procurement Officer", 1),
            ("Project Engineer", 1),
            ("Revenue Officer", 1),
            ("Roaming Coordinator", 1),
            ("Customer Care Executive (Samsung)", 1),
            ("Accountant", 1),
            ("Personal Assistant", 1),
            ("Store Assistant", 1),
            ("Strategy Officer", 1),
            ("System Engineer", 1),
            ("TDA Engineer", 1),
            ("Technical Officer", 1),
            ("Technical Supervisor", 1),
            ("Technician", 1),
            ("VAS Engineer", 1),
            ("Webmaster", 1),
            ("MD", 1),
            ("Power Engineer", 1),
            ("Assistant Inventory Officer", 1),
            ("Assistant Audit Officer", 1),
            ("Technology Analyst, Strategy Planning", 1),
            ("Audit Officer", 1),
            ("Business Analyst, Business Development", 1),
            ("Business Operation Officer", 1),
            ("Digital Marketing Officer", 1),
            ("Assistant Graphic Designer", 1),
            ("O&M Engineer", 1),
            ("Engineer, Mobile Core", 1),
            ("Engineer, RAN", 1),
            ("Software Developer", 1),
            ("Head", 1),
            ("Software Engineer", 1),
            ("Deputy General Manager", 1),
            ("Administrative Assistant", 1),
            ("IP Access Engineer", 1),
            ("Regional Manager", 1),
            ("Business Support Analyst", 1),
            ("Assistant Accounts Officer", 1),
            ("ISP Network Engineer", 1),
            ("Analyst", 1),
            ("Engineer, IP Network", 1),
            ("Officiating Manager", 1),
            ("Acting Manager", 1),
            ("Security Guard", 1),
            ("Caretaker", 1),
            ("Sweeper", 1),
            ("Tea Lady", 1),
            ("Receptionist", 1),
            ("Officiating Technical Suppervisor", 1),
            ("Network Infra Project, Engineer", 1);
                ');
    }
}
