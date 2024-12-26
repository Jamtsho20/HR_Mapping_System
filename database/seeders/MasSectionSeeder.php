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
                ['name' => 'ISP Access Section'],
                ['name' => 'Access Network Section'],
                ['name' => 'Power & Utilities Section'],
            ]],
            ['department_short_name' => 'Marketing', 'sections' => [
                ['name' => 'Regions'],
                ['name' => 'Business Development Section'],
                ['name' => 'Business Operation Section'],
                ['name' => 'Advertisement and Promotion Section'],

            ]],
            ['department_short_name' => 'CNCS', 'sections' => [
                ['name' => 'ISP Core Section'],
                ['name' => 'International Services Section'],
                ['name' => 'Mobile Core Section'],
                ['name' => 'RAN Section'],
            ]],
            ['department_short_name' => 'FD', 'sections' => [
                ['name' => 'Payment Section'],
                ['name' => 'Procurement and Inventory Section'],
                ['name' => 'Revenue and Follow Up Section'],
            ]],
            ['department_short_name' => 'HRAD', 'sections' => [
                ['name' => 'Administration'],
                ['name' => 'Human Resources'],
            ]],
            ['department_short_name' => 'MIS', 'sections' => [
                ['name' => 'VAS'],
                ['name' => 'Billing'],
                ['name' => 'SAS'],
            ]],
            ['department_short_name' => 'SPPD', 'sections' => [
                ['name' => 'Civil Works Section'],
                ['name' => 'Network Infra Projects Section'],
                ['name' => 'Strategic Planning Section'],
            ]],
            ['department_short_name' => 'IAU', 'sections' => [
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


