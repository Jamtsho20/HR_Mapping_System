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
            ['grade' => 'T2', 'steps' => [
                ['name' => 'T2 Step 1', 'starting_salary' => 32327, 'increment' => 808, 'ending_salary' => 40407, 'pay_scale' => '32327 - 808 - 40407', 'created_by' => 1],
                ['name' => 'T2 Step 2', 'starting_salary' => 29389, 'increment' => 735, 'ending_salary' => 36739, 'pay_scale' => '29389 - 735 - 36739', 'created_by' => 1],
                ['name' => 'T2 Step 3', 'starting_salary' => 26717, 'increment' => 668, 'ending_salary' => 33397, 'pay_scale' => '26717 - 668 - 33397', 'created_by' => 1],
                ['name' => 'T2 Step 4', 'starting_salary' => 24288, 'increment' => 607, 'ending_salary' => 30358, 'pay_scale' => '24288 - 607 - 30358', 'created_by' => 1],
                ['name' => 'T2 Step 5', 'starting_salary' => 22080, 'increment' => 552, 'ending_salary' => 27600, 'pay_scale' => '22080 - 552 - 27600', 'created_by' => 1],
                ['name' => 'T2 Step 6', 'starting_salary' => 20073, 'increment' => 502, 'ending_salary' => 25093, 'pay_scale' => '20073 - 502 - 25093', 'created_by' => 1],
                ['name' => 'T2 Step 7', 'starting_salary' => 18248, 'increment' => 456, 'ending_salary' => 22808, 'pay_scale' => '18248 - 456 - 22808', 'created_by' => 1],
                ['name' => 'T2 Step 8', 'starting_salary' => 16589, 'increment' => 415, 'ending_salary' => 20739, 'pay_scale' => '16589 - 415 - 2073', 'created_by' => 1],
                ['name' => 'T2 Step 9', 'starting_salary' => 15081, 'increment' => 377, 'ending_salary' => 18851, 'pay_scale' => '15081 - 377 - 18851', 'created_by' => 1],
            ]],

            ['grade' => 'T1', 'steps' => [
                ['name' => 'T1 Step 1', 'starting_salary' => 47376, 'increment' => 1184, 'ending_salary' => 59216, 'pay_scale' => '47376 - 1184 - 59216', 'created_by' => 1],
                ['name' => 'T1 Step 2', 'starting_salary' => 43069, 'increment' => 1077, 'ending_salary' => 53839, 'pay_scale' => '43069 - 1077 - 53839', 'created_by' => 1],
                ['name' => 'T1 Step 3', 'starting_salary' => 39154, 'increment' => 979, 'ending_salary' => 48944, 'pay_scale' => '39154 - 979 - 48944', 'created_by' => 1],
                ['name' => 'T1 Step 4', 'starting_salary' => 35594, 'increment' => 890, 'ending_salary' => 44494, 'pay_scale' => '35594 - 890 - 44494', 'created_by' => 1],
                ['name' => 'T1 Step 5', 'starting_salary' => 32358, 'increment' => 809, 'ending_salary' => 40448, 'pay_scale' => '32358 - 809 - 40448', 'created_by' => 1],
                ['name' => 'T1 Step 6', 'starting_salary' => 29417, 'increment' => 735, 'ending_salary' => 36767, 'pay_scale' => '29417 - 735 - 36767', 'created_by' => 1],
                ['name' => 'T1 Step 7', 'starting_salary' => 26743, 'increment' => 669, 'ending_salary' => 33433, 'pay_scale' => '26743 - 669 - 33433', 'created_by' => 1],
                ['name' => 'T1 Step 8', 'starting_salary' => 24311, 'increment' => 608, 'ending_salary' => 30391, 'pay_scale' => '24311 - 608 - 30391', 'created_by' => 1],
                ['name' => 'T1 Step 9', 'starting_salary' => 22101, 'increment' => 553, 'ending_salary' => 27631, 'pay_scale' => '22101 - 553 - 27631', 'created_by' => 1],
                ['name' => 'T1 Step 10', 'starting_salary' => 20092, 'increment' => 502, 'ending_salary' => 25112, 'pay_scale' => '20092 - 502 - 25112', 'created_by' => 1],
                ['name' => 'T1 Step 11', 'starting_salary' => 18266, 'increment' => 457, 'ending_salary' => 22836, 'pay_scale' => '18266 - 457 - 22836', 'created_by' => 1],
            ]],
            ['grade' => 'P2', 'steps' => [
                ['name' => 'P2 Step 1', 'starting_salary' => 56986, 'increment' => 1425, 'ending_salary' => 71236, 'pay_scale' => '56986 - 1425 - 71236', 'created_by' => 1],
                ['name' => 'P2 Step 2', 'starting_salary' => 51806, 'increment' => 1295, 'ending_salary' => 64756, 'pay_scale' => '51806 - 1295 - 64756', 'created_by' => 1],
                ['name' => 'P2 Step 3', 'starting_salary' => 47096, 'increment' => 1177, 'ending_salary' => 58866, 'pay_scale' => '47096 - 1177 - 58866', 'created_by' => 1],
                ['name' => 'P2 Step 4', 'starting_salary' => 42815, 'increment' => 1070, 'ending_salary' => 53515, 'pay_scale' => '42815 - 1070 - 53515', 'created_by' => 1],
                ['name' => 'P2 Step 5', 'starting_salary' => 38922, 'increment' => 973, 'ending_salary' => 48652, 'pay_scale' => '38922 - 973 - 48652', 'created_by' => 1],
                ['name' => 'P2 Step 6', 'starting_salary' => 35384, 'increment' => 885, 'ending_salary' => 44234, 'pay_scale' => '35384 - 885 - 44234', 'created_by' => 1],
                ['name' => 'P2 Step 7', 'starting_salary' => 32167, 'increment' => 804, 'ending_salary' => 40207, 'pay_scale' => '32167 - 804 - 40207', 'created_by' => 1],
                ['name' => 'P2 Step 8', 'starting_salary' => 29243, 'increment' => 731, 'ending_salary' => 36553, 'pay_scale' => '29243 - 731 - 36553', 'created_by' => 1],
                ['name' => 'P2 Step 9', 'starting_salary' => 26585, 'increment' => 665, 'ending_salary' => 33235, 'pay_scale' => '26585 - 665 - 33235', 'created_by' => 1],
            ]],
            ['grade' => 'P1', 'steps' => [
                ['name' => 'P1 Step 1', 'starting_salary' => 88470, 'increment' => 2212, 'ending_salary' => 110590, 'pay_scale' => '88470 - 2212 - 110590', 'created_by' => 1],
                ['name' => 'P1 Step 2', 'starting_salary' => 80427, 'increment' => 2011, 'ending_salary' => 100537, 'pay_scale' => '80427 - 2011 - 100537', 'created_by' => 1],
                ['name' => 'P1 Step 3', 'starting_salary' => 73116, 'increment' => 1828, 'ending_salary' => 91396, 'pay_scale' => '73116 - 1828 - 91396', 'created_by' => 1],
                ['name' => 'P1 Step 4', 'starting_salary' => 66469, 'increment' => 1662, 'ending_salary' => 83089, 'pay_scale' => '66469 - 1662 - 83089', 'created_by' => 1],
                ['name' => 'P1 Step 5', 'starting_salary' => 60426, 'increment' => 1511, 'ending_salary' => 75536, 'pay_scale' => '60426 - 1511 - 75536', 'created_by' => 1],
                ['name' => 'P1 Step 6', 'starting_salary' => 54933, 'increment' => 1373, 'ending_salary' => 68663, 'pay_scale' => '54933 - 1373 - 68663', 'created_by' => 1],
                ['name' => 'P1 Step 7', 'starting_salary' => 49939, 'increment' => 1248, 'ending_salary' => 62419, 'pay_scale' => '49939 - 1248 - 62419', 'created_by' => 1],
                ['name' => 'P1 Step 8', 'starting_salary' => 45399, 'increment' => 1135, 'ending_salary' => 56749, 'pay_scale' => '45399 - 1135 - 56749', 'created_by' => 1],
                ['name' => 'P1 Step 9', 'starting_salary' => 41272, 'increment' => 1032, 'ending_salary' => 51592, 'pay_scale' => '41272 - 1032 - 51592', 'created_by' => 1],
                ['name' => 'P1 Step 10', 'starting_salary' => 37520, 'increment' => 938, 'ending_salary' => 46900, 'pay_scale' => '37520 - 938 - 46900', 'created_by' => 1],
                ['name' => 'P1 Step 11', 'starting_salary' => 34109, 'increment' => 853, 'ending_salary' => 42639, 'pay_scale' => '34109 - 853 - 42639', 'created_by' => 1],
                ['name' => 'P1 Step 12', 'starting_salary' => 31008, 'increment' => 775, 'ending_salary' => 38758, 'pay_scale' => '31008 - 775 - 38758', 'created_by' => 1],
            ]],
            ['grade' => 'E0', 'steps' => [
                ['name' => 'E0 Step 1', 'starting_salary' => NULL, 'increment' => NULL, 'ending_salary' => NULL, 'pay_scale' => NULL, 'created_by' => 1],
                ['name' => 'E0 Step 2', 'starting_salary' => NULL, 'increment' => NULL, 'ending_salary' => NULL, 'pay_scale' => NULL, 'created_by' => 1],
                ['name' => 'E0 Step 3', 'starting_salary' => NULL, 'increment' => NULL, 'ending_salary' => NULL, 'pay_scale' => NULL, 'created_by' => 1],
            ]],
              ['grade' => 'S', 'steps' => [
                ['name' => 'S Step 1', 'starting_salary' => 15979, 'increment' => 399, 'ending_salary' => 19969, 'pay_scale' => '15979 - 399 - 19969', 'created_by' => 1],
                ['name' => 'S Step 2', 'starting_salary' => 14523, 'increment' => 363, 'ending_salary' => 18153, 'pay_scale' => '14523 - 363 - 18153', 'created_by' => 1],
                ['name' => 'S Step 3', 'starting_salary' => 13202, 'increment' => 330, 'ending_salary' => 16502, 'pay_scale' => '13202 - 330 - 16502', 'created_by' => 1],
                ['name' => 'S Step 4', 'starting_salary' => 12007, 'increment' => 300, 'ending_salary' => 15007, 'pay_scale' => '12007 - 300 - 15007', 'created_by' => 1],
                ['name' => 'S Step 5', 'starting_salary' => 11460, 'increment' => 287, 'ending_salary' => 14330, 'pay_scale' => '11460 - 287 - 14330', 'created_by' => 1],
                ['name' => 'S Step 6', 'starting_salary' => 10920, 'increment' => 273, 'ending_salary' => 13650, 'pay_scale' => '10920 - 273 - 13650', 'created_by' => 1],
                ['name' => 'S Step 7', 'starting_salary' => 10375, 'increment' => 259, 'ending_salary' => 12965, 'pay_scale' => '10375 - 259 - 12965', 'created_by' => 1],
            ]],
            ['grade' => 'P', 'steps' => [
                ['name' => 'P Step 1', 'starting_salary' => 88470, 'increment' => 2212, 'ending_salary' => 110588,'pay_scale' => '88470 - 2212 - 110588', 'created_by' => 1],
                ['name' => 'P Step 2', 'starting_salary' => 80427, 'increment' => 2011, 'ending_salary' => 100534, 'pay_scale' => '80427 - 2011 - 100534', 'created_by' => 1],
                ['name' => 'P Step 3', 'starting_salary' => 73116, 'increment' => 1828, 'ending_salary' => 91395, 'pay_scale' => '73116 - 1828 - 91395', 'created_by' => 1],
                ['name' => 'P Step 4', 'starting_salary' => 66469, 'increment' => 1662, 'ending_salary' => 83086, 'pay_scale' => '66469 - 1662 - 83086', 'created_by' => 1],
                ['name' => 'P Step 5', 'starting_salary' => 60426, 'increment' => 1511, 'ending_salary' => 75533, 'pay_scale' => '60426 - 1511 - 75533', 'created_by' => 1],
                ['name' => 'P Step 6', 'starting_salary' => 54933, 'increment' => 1373, 'ending_salary' => 68666, 'pay_scale' => '54933 - 1373 - 68666', 'created_by' => 1],
                ['name' => 'P Step 7', 'starting_salary' => 49939, 'increment' => 1248, 'ending_salary' => 62424, 'pay_scale' => '49939 - 1248 - 62424', 'created_by' => 1],
                ['name' => 'P Step 8', 'starting_salary' => 45399, 'increment' => 1135, 'ending_salary' => 56749, 'pay_scale' => '45399 - 1135 - 56749', 'created_by' => 1],
                ['name' => 'P Step 9', 'starting_salary' => 41272, 'increment' => 1032, 'ending_salary' => 51590, 'pay_scale' => '41272 - 1032 - 51590', 'created_by' => 1],
                ['name' => 'P Step 10', 'starting_salary' => 37520, 'increment' => 938, 'ending_salary' => 46900, 'pay_scale' => '37520 - 938 - 46900', 'created_by' => 1],
                ['name' => 'P Step 11', 'starting_salary' => 34109, 'increment' => 853, 'ending_salary' => 42636, 'pay_scale' => '34109 - 853 - 42636', 'created_by' => 1],
                ['name' => 'P Step 12', 'starting_salary' => 31008, 'increment' => 775, 'ending_salary' => 38760, 'pay_scale' => '31008 - 775 - 38760', 'created_by' => 1],
                ['name' => 'P Step 13', 'starting_salary' => 28190, 'increment' => 705, 'ending_salary' => 35238, 'pay_scale' => '28190 - 705 - 35238', 'created_by' => 1],
            ]],
            ['grade' => 'T', 'steps' => [
                ['name' => 'T Step 1', 'starting_salary' => 47376, 'increment' => 1184, 'ending_salary' => 59220, 'pay_scale' => '47376 - 1184 - 59220', 'created_by' => 1],
                ['name' => 'T Step 2', 'starting_salary' => 43069, 'increment' => 1077, 'ending_salary' => 53836, 'pay_scale' => '43069 - 1077 - 53836', 'created_by' => 1],
                ['name' => 'T Step 3', 'starting_salary' => 39154, 'increment' => 979, 'ending_salary' => 48942, 'pay_scale' => '39154 - 979 - 48942', 'created_by' => 1],
                ['name' => 'T Step 4', 'starting_salary' => 35594, 'increment' => 890, 'ending_salary' => 44493, 'pay_scale' => '35594 - 890 - 44493', 'created_by' => 1],
                ['name' => 'T Step 5', 'starting_salary' => 32358, 'increment' => 809, 'ending_salary' => 40448, 'pay_scale' => '32358 - 809 - 40448', 'created_by' => 1],
                ['name' => 'T Step 6', 'starting_salary' => 29417, 'increment' => 735, 'ending_salary' => 36771, 'pay_scale' => '29417 - 735 - 36771', 'created_by' => 1],
                ['name' => 'T Step 7', 'starting_salary' => 26743, 'increment' => 669, 'ending_salary' => 33428, 'pay_scale' => '26743 - 669 - 33428', 'created_by' => 1],
                ['name' => 'T Step 8', 'starting_salary' => 24311, 'increment' => 608, 'ending_salary' => 30389, 'pay_scale' => '24311 - 608 - 30389', 'created_by' => 1],
                ['name' => 'T Step 9', 'starting_salary' => 22101, 'increment' => 553, 'ending_salary' => 27627, 'pay_scale' => '22101 - 553 - 27627', 'created_by' => 1],
                ['name' => 'T Step 10', 'starting_salary' => 20092, 'increment' => 502, 'ending_salary' => 25115, 'pay_scale' => '20092 - 502 - 25115', 'created_by' => 1],
                ['name' => 'T Step 11', 'starting_salary' => 18266, 'increment' => 457, 'ending_salary' => 22832, 'pay_scale' => '18266 - 457 - 22832', 'created_by' => 1],
                ['name' => 'T Step 12', 'starting_salary' => 16605, 'increment' => 415, 'ending_salary' => 20736, 'pay_scale' => '16605 - 415 - 20736', 'created_by' => 1],
                ['name' => 'T Step 13', 'starting_salary' => 15095, 'increment' => 377, 'ending_salary' => 18851, 'pay_scale' => '15095 - 377 - 18851', 'created_by' => 1],
            ]],
        ];

        foreach ($data as $gradeData) {
            $gradeId = $grades->get($gradeData['grade']);
            if ($gradeId) {
                foreach ($gradeData['steps'] as $step) {
                    DB::table('mas_grade_steps')->insert([
                        'mas_grade_id' => $gradeId,
                        'name' => $step['name'],
                        'starting_salary' => $step['starting_salary'],
                        'increment' => $step['increment'],
                        'ending_salary' => $step['ending_salary'],
                        'pay_scale' => "{$step['starting_salary']} - {$step['increment']} - {$step['ending_salary']}",
                        'created_by' => 1, 
                    ]);
                }
            }
        }
    }
}
