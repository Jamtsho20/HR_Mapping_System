<?php

namespace App\Console\Commands;

use App\Models\AttendanceDetail;
use App\Models\EmployeeAttendance;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Console\Command;

class UploadAttendanceDetailsDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload-attendance-details-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload attendance details daily at 2 am in the morning for each employee section wise for current day/date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDay = (int) now()->format('d'); // eg. 17
        $currentMonth = now()->format('m-Y');
        $attendanceService = new AttendanceService();

        User::with(['empJob.office'])
            ->whereNotIn('id', [1, 2])
            ->whereIsActive(1)
            ->chunk(100, function ($employees) use ($currentDay, $currentMonth, $attendanceService) {
                $insertData = [];

                foreach ($employees as $employee) {
                    $departmentId = optional($employee->empJob)->mas_department_id;
                    $sectionId = optional($employee->empJob)->mas_section_id;
                    $regionId = optional(optional($employee->empJob)->office)->mas_region_id;

                    // if ((!$departmentId && !$sectionId) || !$regionId) {
                    if (!$regionId) {
                        continue; // skip if necessary data is missing
                    }

                    // fetch daily emp attendance  based on employee section or department
                    
                    $empAttendance = EmployeeAttendance::with(['dailyAttendances' => function ($query) use ($currentDay) {
                        $query->where('day', $currentDay);
                    }])
                    ->where('for_month', $currentMonth)
                    ->first();

                    $dailyAttendance = optional($empAttendance)->dailyAttendances->first();
                    
                    if (!$dailyAttendance) {
                        continue; // skip if no matching daily attendance
                    }
                    
                    $attendanceStatus = $attendanceService->getAttendanceStatus($employee->id, $regionId);

                    $insertData[] = [
                        'daily_attendance_id' => $dailyAttendance->id,
                        'employee_id' => $employee->id,
                        'department_id' => $departmentId ?? null,
                        'section_id' => $sectionId ?? null,
                        'check_in_at' => null,
                        'check_out_at' => null,
                        'check_in_ip' => null,
                        'check_out_ip' => null,
                        'attendance_status_id' => $attendanceStatus,
                        'created_by' => 1,
                        'created_at' => now(),
                    ];
                }

                if (!empty($insertData)) {  
                    AttendanceDetail::insert($insertData);
                }
            });

        $this->info('Employee attendance details created successfully for ' . now() . '.');
    }

}
