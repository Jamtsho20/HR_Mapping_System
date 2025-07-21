<?php

namespace App\Console\Commands;

use App\Models\DailyAttendance;
use App\Models\EmployeeAttendance;
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
        try{
            $currentMonthAttendance = EmployeeAttendance::where('for_month', '=', $currentMonth)->first();
            if($currentMonthAttendance) {
                $this->insertData($currentMonthAttendance, $daysInMonth);
            }else {
                $monthlyAttendance = EmployeeAttendance::create([
                    'for_month' => $currentMonth,
                    'created_by' => 1,
                    'created_at' => now()
                ]);

                $this->insertData($monthlyAttendance, $daysInMonth);

                $this->info('Attendance for month ' . $currentMonth . ' created successfully');
                // \Log::info('Attendance for month ' . $currentMonth . ' created successfully');
            }
        }catch(\Exception $e){
            \Log::info('Attendance for month ' . $currentMonth . ' failed to upload: ' . $e->getMessage());
        }
    }

    private function insertData($monthlyAttendance, $daysInMonth){
        $insertData = [];
        // loop for each day of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $insertData[] = [
                'attendance_id' => $monthlyAttendance->id,
                'day'           => $day,
                'status'        => 1,
                'created_by'    => 1,
                'created_at'    => now(),
            ];
        }

        //bulk insert into daily_attendance table after preparing data for every section and for each day in a month
        if(!empty($insertData)){
            DailyAttendance::insert($insertData);
        }
    }
}
