<?php

namespace App\Console\Commands;

use App\Models\DailyAttendance;
use App\Models\EmployeeAttendance;
use App\Models\MasDepartment;
use App\Models\MasSection;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UploadMonthlyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload-monthly-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload monthly attendance at 01:00 am monthly (starting of the month).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentMonth = Carbon::now()->format('m-Y');
        // total number of days in a month
        $daysInMonth = daysInMonth(now()); 

        //only create attendance for month if attendance for month is not created.
        $currentMonthAttendance = EmployeeAttendance::where('for_month', '=', $currentMonth)->first();
        if(!$currentMonthAttendance){
            $monthlyAttendance = EmployeeAttendance::create([
                'for_month' => $currentMonth,
                'created_by' => 1,
                'created_at' => now()
            ]);

            $departments = MasDepartment::whereStatus(1)->get();
            $sections = MasSection::whereStatus(1)->get();
            $insertData = [];
            // loop for each day of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                // here daily attendance is inserted for ection as well as for department since attendance submission need to done section wise while some employee donot have section
                // insert data for each section for the day
                foreach ($sections as $section) {
                    $insertData[] = [
                        'attendance_id' => $monthlyAttendance->id,
                        'department_id' => $section->mas_department_id,
                        'section_id'    => $section->id,
                        'day'           => $day,
                        'status'        => 1,
                        'created_by'    => 1,
                        'created_at'    => now(),
                    ];
                }
                // insert daily attendance for each department wise so that it can also handle for those employee who donot have sections like dept head, MD
                foreach($departments as $department){
                    $insertData[] = [
                        'attendance_id' => $monthlyAttendance->id,
                        'department_id' => $department->id ?? null,
                        'section_id'    => null,
                        'day'           => $day,
                        'status'        => 1,
                        'created_by'    => 1,
                        'created_at'    => now(),
                    ];
                }
            }

            //bulk insert into daily_attendance table after preparing data for every section and for each day in a month
            if(!empty($insertData)){
                DailyAttendance::insert($insertData);
            }

            $this->info('Attendance for month ' . $currentMonth . ' created successfully');
            // \Log::info('Attendance for month ' . $currentMonth . ' created successfully');
        }else{
            $this->info('Attendance for month ' . $currentMonth . ' already exists.');
            // \Log::info('Attendance for month ' . $currentMonth . ' already exists.');
        }
    }
}
