<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasGradeStepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        $grades = DB::table('mas_grades')->pluck('id', 'name'); 
        
        $data = [
            ['name' => 'T2', 'steps' => [
                ['name' => 'T2 Step 1', 'starting_salary' => 42060, 'increment' => 1050, 'ending_salary' => 52560,  'created_by' => 1],
                ['name' => 'T2 Step 2', 'starting_salary' => 38240, 'increment' => 960, 'ending_salary' => 47840,  'created_by' => 1],
                ['name' => 'T2 Step 3', 'starting_salary' => 34765, 'increment' => 870, 'ending_salary' => 43465,  'created_by' => 1],
                ['name' => 'T2 Step 4', 'starting_salary' => 31605, 'increment' => 790, 'ending_salary' => 39505,  'created_by' => 1],
                ['name' => 'T2 Step 5', 'starting_salary' => 28665, 'increment' => 720, 'ending_salary' => 35865,  'created_by' => 1],
                ['name' => 'T2 Step 6', 'starting_salary' => 26060, 'increment' => 650, 'ending_salary' => 32560,  'created_by' => 1],
                ['name' => 'T2 Step 7', 'starting_salary' => 23695, 'increment' => 590, 'ending_salary' => 29595,  'created_by' => 1],
                ['name' => 'T2 Step 8', 'starting_salary' => 21540, 'increment' => 540, 'ending_salary' => 26940,  'created_by' => 1],
                ['name' => 'T2 Step 9', 'starting_salary' => 19585, 'increment' => 490, 'ending_salary' => 24485,  'created_by' => 1],
                ['name' => 'T2 Step 10', 'starting_salary' => 17800, 'increment' => 450, 'ending_salary' => 22300,  'created_by' => 1],
                ['name' => 'T2 Step 11', 'starting_salary' => 16180, 'increment' => 400, 'ending_salary' => 20180,  'created_by' => 1],
            ]],

            ['name' => 'T1', 'steps' => [
                ['name' => 'T1 Step 1', 'starting_salary' => 61600, 'increment' => 1540, 'ending_salary' => 77000,  'created_by' => 1],
                ['name' => 'T1 Step 2', 'starting_salary' => 56000, 'increment' => 1400, 'ending_salary' => 70000,  'created_by' => 1],
                ['name' => 'T1 Step 3', 'starting_salary' => 50190, 'increment' => 1270, 'ending_salary' => 63610,  'created_by' => 1],
                ['name' => 'T1 Step 4', 'starting_salary' => 46280, 'increment' => 1160, 'ending_salary' => 57880,  'created_by' => 1],
                ['name' => 'T1 Step 5', 'starting_salary' => 42070, 'increment' => 1050, 'ending_salary' => 52570,  'created_by' => 1],
                ['name' => 'T1 Step 6', 'starting_salary' => 38245, 'increment' => 960, 'ending_salary' => 47845,  'created_by' => 1],
                ['name' => 'T1 Step 7', 'starting_salary' => 34765, 'increment' => 870, 'ending_salary' => 43465,  'created_by' => 1],
                ['name' => 'T1 Step 8', 'starting_salary' => 31605, 'increment' => 790, 'ending_salary' => 39505,  'created_by' => 1],
                ['name' => 'T1 Step 9', 'starting_salary' => 28665, 'increment' => 720, 'ending_salary' => 35865,  'created_by' => 1],
                ['name' => 'T1 Step 10', 'starting_salary' => 26060, 'increment' => 650, 'ending_salary' => 32560,  'created_by' => 1],
                ['name' => 'T1 Step 11', 'starting_salary' => 23695, 'increment' => 590, 'ending_salary' => 29595,  'created_by' => 1],
            ]],
            // ['name' => 'P2', 'steps' => [
            //     ['name' => 'P2 Step 1', 'starting_salary' => 56986, 'increment' => 1425, 'ending_salary' => 71236, 'pay_scale' => '56986 - 1425 - 71236', 'created_by' => 1],
            //     ['name' => 'P2 Step 2', 'starting_salary' => 51806, 'increment' => 1295, 'ending_salary' => 64756, 'pay_scale' => '51806 - 1295 - 64756', 'created_by' => 1],
            //     ['name' => 'P2 Step 3', 'starting_salary' => 47096, 'increment' => 1177, 'ending_salary' => 58866, 'pay_scale' => '47096 - 1177 - 58866', 'created_by' => 1],
            //     ['name' => 'P2 Step 4', 'starting_salary' => 42815, 'increment' => 1070, 'ending_salary' => 53515, 'pay_scale' => '42815 - 1070 - 53515', 'created_by' => 1],
            //     ['name' => 'P2 Step 5', 'starting_salary' => 38922, 'increment' => 973, 'ending_salary' => 48652, 'pay_scale' => '38922 - 973 - 48652', 'created_by' => 1],
            //     ['name' => 'P2 Step 6', 'starting_salary' => 35384, 'increment' => 885, 'ending_salary' => 44234, 'pay_scale' => '35384 - 885 - 44234', 'created_by' => 1],
            //     ['name' => 'P2 Step 7', 'starting_salary' => 32167, 'increment' => 804, 'ending_salary' => 40207, 'pay_scale' => '32167 - 804 - 40207', 'created_by' => 1],
            //     ['name' => 'P2 Step 8', 'starting_salary' => 29243, 'increment' => 731, 'ending_salary' => 36553, 'pay_scale' => '29243 - 731 - 36553', 'created_by' => 1],
            //     ['name' => 'P2 Step 9', 'starting_salary' => 26585, 'increment' => 665, 'ending_salary' => 33235, 'pay_scale' => '26585 - 665 - 33235', 'created_by' => 1],
            // ]],
            // ['name' => 'P1', 'steps' => [
            //     ['name' => 'P1 Step 1', 'starting_salary' => 88470, 'increment' => 2212, 'ending_salary' => 110590, 'pay_scale' => '88470 - 2212 - 110590', 'created_by' => 1],
            //     ['name' => 'P1 Step 2', 'starting_salary' => 80427, 'increment' => 2011, 'ending_salary' => 100537, 'pay_scale' => '80427 - 2011 - 100537', 'created_by' => 1],
            //     ['name' => 'P1 Step 3', 'starting_salary' => 73116, 'increment' => 1828, 'ending_salary' => 91396, 'pay_scale' => '73116 - 1828 - 91396', 'created_by' => 1],
            //     ['name' => 'P1 Step 4', 'starting_salary' => 66469, 'increment' => 1662, 'ending_salary' => 83089, 'pay_scale' => '66469 - 1662 - 83089', 'created_by' => 1],
            //     ['name' => 'P1 Step 5', 'starting_salary' => 60426, 'increment' => 1511, 'ending_salary' => 75536, 'pay_scale' => '60426 - 1511 - 75536', 'created_by' => 1],
            //     ['name' => 'P1 Step 6', 'starting_salary' => 54933, 'increment' => 1373, 'ending_salary' => 68663, 'pay_scale' => '54933 - 1373 - 68663', 'created_by' => 1],
            //     ['name' => 'P1 Step 7', 'starting_salary' => 49939, 'increment' => 1248, 'ending_salary' => 62419, 'pay_scale' => '49939 - 1248 - 62419', 'created_by' => 1],
            //     ['name' => 'P1 Step 8', 'starting_salary' => 45399, 'increment' => 1135, 'ending_salary' => 56749, 'pay_scale' => '45399 - 1135 - 56749', 'created_by' => 1],
            //     ['name' => 'P1 Step 9', 'starting_salary' => 41272, 'increment' => 1032, 'ending_salary' => 51592, 'pay_scale' => '41272 - 1032 - 51592', 'created_by' => 1],
            //     ['name' => 'P1 Step 10', 'starting_salary' => 37520, 'increment' => 938, 'ending_salary' => 46900, 'pay_scale' => '37520 - 938 - 46900', 'created_by' => 1],
            //     ['name' => 'P1 Step 11', 'starting_salary' => 34109, 'increment' => 853, 'ending_salary' => 42639, 'pay_scale' => '34109 - 853 - 42639', 'created_by' => 1],
            //     ['name' => 'P1 Step 12', 'starting_salary' => 31008, 'increment' => 775, 'ending_salary' => 38758, 'pay_scale' => '31008 - 775 - 38758', 'created_by' => 1],
            // ]],
            ['name' => 'E0', 'steps' => [
                ['name' => 'E0 Step 1', 'starting_salary' => 0, 'increment' => 0, 'ending_salary' => 0, 'created_by' => 1],
                ['name' => 'E0 Step 2', 'starting_salary' => 0, 'increment' => 0, 'ending_salary' => 0, 'created_by' => 1],
                ['name' => 'E0 Step 3', 'starting_salary' => 0, 'increment' => 0, 'ending_salary' => 0, 'created_by' => 1],
            ]],
              ['name' => 'S', 'steps' => [
                ['name' => 'S Step 1', 'starting_salary' => 15979, 'increment' => 399, 'ending_salary' => 19973,  'created_by' => 1],
                ['name' => 'S Step 2', 'starting_salary' => 14523, 'increment' => 363, 'ending_salary' => 18154,  'created_by' => 1],
                ['name' => 'S Step 3', 'starting_salary' => 13202, 'increment' => 330, 'ending_salary' => 16502,  'created_by' => 1],
                ['name' => 'S Step 4', 'starting_salary' => 12007, 'increment' => 300, 'ending_salary' => 15009,  'created_by' => 1],
                ['name' => 'S Step 5', 'starting_salary' => 11460, 'increment' => 287, 'ending_salary' => 14325,  'created_by' => 1],
                ['name' => 'S Step 6', 'starting_salary' => 10920, 'increment' => 273, 'ending_salary' => 13650,  'created_by' => 1],
                ['name' => 'S Step 7', 'starting_salary' => 10375, 'increment' => 259, 'ending_salary' => 12968,  'created_by' => 1],
            ]],
            ['name' => 'P', 'steps' => [
                ['name' => 'P Step 1 ', 'starting_salary' => 109828, 'increment' => 2746, 'ending_salary' => 137284, 'created_by' => 1],
                ['name' => 'P Step 2', 'starting_salary' => 98060, 'increment' => 2452, 'ending_salary' => 122575,  'created_by' => 1],
                ['name' => 'P Step 3', 'starting_salary' => 87554, 'increment' => 2189, 'ending_salary' => 109442,  'created_by' => 1],
                ['name' => 'P Step 4', 'starting_salary' => 78173, 'increment' => 1954, 'ending_salary' => 97716,  'created_by' => 1],
                ['name' => 'P Step 5', 'starting_salary' => 69797, 'increment' => 1745, 'ending_salary' => 87247,  'created_by' => 1],
                ['name' => 'P Step 6', 'starting_salary' => 62319, 'increment' => 1558, 'ending_salary' => 77899,  'created_by' => 1],
                ['name' => 'P Step 7', 'starting_salary' => 55642, 'increment' => 1391, 'ending_salary' => 69553,  'created_by' => 1],
                ['name' => 'P Step 8', 'starting_salary' => 49680, 'increment' => 1242, 'ending_salary' => 62101,  'created_by' => 1],
                ['name' => 'P Step 9', 'starting_salary' => 44680, 'increment' => 1109, 'ending_salary' => 55447,  'created_by' => 1],
                ['name' => 'P Step 10', 'starting_salary' => 39605, 'increment' => 990, 'ending_salary' => 49506,  'created_by' => 1],
                ['name' => 'P Step 11', 'starting_salary' => 35362, 'increment' => 884, 'ending_salary' => 44202,  'created_by' => 1],
                ['name' => 'P Step 12', 'starting_salary' => 31573, 'increment' => 789, 'ending_salary' => 39466,  'created_by' => 1],
                ['name' => 'P Step 13', 'starting_salary' => 28190, 'increment' => 705, 'ending_salary' => 35328,  'created_by' => 1],
            ]],
            // ['name' => 'T', 'steps' => [
            //     ['name' => 'T Step 1', 'starting_salary' => 47376, 'increment' => 1184, 'ending_salary' => 59220, 'pay_scale' => '47376 - 1184 - 59220', 'created_by' => 1],
            //     ['name' => 'T Step 2', 'starting_salary' => 43069, 'increment' => 1077, 'ending_salary' => 53836, 'pay_scale' => '43069 - 1077 - 53836', 'created_by' => 1],
            //     ['name' => 'T Step 3', 'starting_salary' => 39154, 'increment' => 979, 'ending_salary' => 48942, 'pay_scale' => '39154 - 979 - 48942', 'created_by' => 1],
            //     ['name' => 'T Step 4', 'starting_salary' => 35594, 'increment' => 890, 'ending_salary' => 44493, 'pay_scale' => '35594 - 890 - 44493', 'created_by' => 1],
            //     ['name' => 'T Step 5', 'starting_salary' => 32358, 'increment' => 809, 'ending_salary' => 40448, 'pay_scale' => '32358 - 809 - 40448', 'created_by' => 1],
            //     ['name' => 'T Step 6', 'starting_salary' => 29417, 'increment' => 735, 'ending_salary' => 36771, 'pay_scale' => '29417 - 735 - 36771', 'created_by' => 1],
            //     ['name' => 'T Step 7', 'starting_salary' => 26743, 'increment' => 669, 'ending_salary' => 33428, 'pay_scale' => '26743 - 669 - 33428', 'created_by' => 1],
            //     ['name' => 'T Step 8', 'starting_salary' => 24311, 'increment' => 608, 'ending_salary' => 30389, 'pay_scale' => '24311 - 608 - 30389', 'created_by' => 1],
            //     ['name' => 'T Step 9', 'starting_salary' => 22101, 'increment' => 553, 'ending_salary' => 27627, 'pay_scale' => '22101 - 553 - 27627', 'created_by' => 1],
            //     ['name' => 'T Step 10', 'starting_salary' => 20092, 'increment' => 502, 'ending_salary' => 25115, 'pay_scale' => '20092 - 502 - 25115', 'created_by' => 1],
            //     ['name' => 'T Step 11', 'starting_salary' => 18266, 'increment' => 457, 'ending_salary' => 22832, 'pay_scale' => '18266 - 457 - 22832', 'created_by' => 1],
            //     ['name' => 'T Step 12', 'starting_salary' => 16605, 'increment' => 415, 'ending_salary' => 20736, 'pay_scale' => '16605 - 415 - 20736', 'created_by' => 1],
            //     ['name' => 'T Step 13', 'starting_salary' => 15095, 'increment' => 377, 'ending_salary' => 18851, 'pay_scale' => '15095 - 377 - 18851', 'created_by' => 1],
            // ]],
            ['name' => 'GSSG', 'steps' => [
                ['name' => 'Security Guard', 'starting_salary' => 10625, 'increment' => 266, 'ending_salary' => 0, 'created_by' => 1],
                ['name' => 'Tea Lady', 'starting_salary' => 9375, 'increment' => 234, 'ending_salary' => 0, 'created_by' => 1],
            ]],

        ];

        foreach ($data as $gradeData) {
            $gradeId = $grades->get($gradeData['name']);
            if ($gradeId) {
                foreach ($gradeData['steps'] as $step) {
                    DB::table('mas_grade_steps')->insert([
                        'mas_grade_id' => $gradeId,
                        'name' => $step['name'],
                        'starting_salary' => $step['starting_salary'],
                        'increment' => $step['increment'],
                        'ending_salary' => $step['ending_salary'],
                        'created_by' => 1, 
                    ]);
                }
            }
        }
    }
}
