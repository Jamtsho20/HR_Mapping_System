<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        $departments = DB::table('mas_departments')->pluck('id', 'short_name'); 
        
        $data = [
            ['department_short_name' => 'AND', 'sections' => [
                ['name' => 'Access Network Department'],
                ['name' => 'ISP Access'],
                ['name' => 'Access Network'],
                ['name' => 'Power & Utilities'],
            ]],
            ['department_short_name' => 'Marketing', 'sections' => [
                ['name' => 'Marketing'],
                ['name' => 'Business Development'],
                ['name' => 'Business Operation'],
                ['name' => 'Advertisement and Promotion'],
                ['name' => 'Sales & Operation'],
                ['name' => 'Marketing Department'],
                
            ]],
            ['department_short_name' => 'CNCS', 'sections' => [
                ['name' => 'ISP Core'],
                ['name' => 'International Services'],
                ['name' => 'Mobile Core'],
                ['name' => 'Core Network and Carrier Services Department'],
                ['name' => 'PS Core'],
                ['name' => 'RAN Core'],
            ]],
            ['department_short_name' => 'FD', 'sections' => [
                ['name' => 'Payment'],
                ['name' => 'Procurement and Inventory'],
                ['name' => 'Revenue and Follow Up'],
                ['name' => 'Finance Department'],
            ]],
            ['department_short_name' => 'HRAD', 'sections' => [
                ['name' => 'Administration'],
                ['name' => 'Human Resources'],
                ['name' => 'Human Resource & Administration Department'],
            ]],
            ['department_short_name' => 'MIS', 'sections' => [
                ['name' => 'ERP'],
                ['name' => 'VAS'],
                ['name' => 'Billing'],
                ['name' => 'SAAS'],
                ['name' => 'Management Information System Department'],
            ]],
            ['department_short_name' => 'SPPD', 'sections' => [
                ['name' => 'B2B Projects'],
                ['name' => 'Civil Works'],
                ['name' => 'Network Infra Projects'],
                ['name' => 'Strategy Planning'],
                ['name' => 'Application & Software Projects'],
                ['name' => 'Strategic Planning'],
                ['name' => 'Strategic Planning and Projects Department'],
            ]],
            ['department_short_name' => 'IAU', 'sections' => [
                ['name' => 'Internal Audit'],
                ['name' => 'Internal Audit Unit'],
            ]],
            
        ];

        foreach ($data as $departmentData) {
            $departmentId = $departments->get($departmentData['department_short_name']);
            if ($departmentId) {
                foreach ($departmentData['sections'] as $section) {
                    DB::table('mas_sections')->insert([
                        'mas_department_id' => $departmentId,
                        'name' => $section['name'],
                        'created_by' => 1,
                    ]);
                }
            }
        }
    }
}
