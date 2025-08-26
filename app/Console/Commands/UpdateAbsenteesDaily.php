<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AttendanceDetail;
use App\Models\EmployeeAttendance;

class UpdateAbsenteesDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-absentees-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    [{"date":"2025-08-25 22:35:03","attendance_status_id":2,"remarks":"Marked absent on 2025-08-25 22:35:03 as he\/she didn`t checked in for the day (System generated).","updated_by":1}]
    public function handle()
    {
        $currentDay = (int) now()->format('d'); // eg. 17
        $currentMonth = now()->format('m-Y');

        $empAttendance = EmployeeAttendance::with(['dailyAttendances' => function ($query) use ($currentDay) {
                        $query->where('day', $currentDay);
                    }])
                    ->where('for_month', $currentMonth)
                    ->first();
        $dailyAttendance = optional($empAttendance)->dailyAttendances->first();
        if($dailyAttendance){
            $attendanceDetails = AttendanceDetail::where('daily_attendance_id', $dailyAttendance->id)->where('attendance_status_id', CREATED_STATUS)->get();
            foreach($attendanceDetails as $detail){
                $remarks = 'marked as absent for ' . now()->format('Y-m-d') . ' as he/she didn`t clocked-in for the day (System generated).';
                $history = $detail->update_history ? json_decode($detail->update_history, true) : [];
                $history[] = [
                    'date' => now()->toDateTimeString(),
                    'attendance_status_id' => ABSENT_STATUS,
                    'remarks' => $remarks,
                    'updated_by' => 1,
                ];
                $detail->attendance_status_id = ABSENT_STATUS; 
                $detail->remarks = $remarks;
                $detail->updated_by = 1;
                $detail->updated_at = now();
                $detail->update_history = json_encode($history);
                $detail->save();
            }
        }
        
        $this->info('Attendance details updated successfully for ' . now() . '.');
    }
}
