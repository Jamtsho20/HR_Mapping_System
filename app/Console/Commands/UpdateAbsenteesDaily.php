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
        if ($dailyAttendance) { //here need to check for HDW as well to marked as absent at end of the day
            $attendanceDetails = AttendanceDetail::where('daily_attendance_id', $dailyAttendance->id)->whereIn('attendance_status_id', [CREATED_STATUS, HALF_DAY_WEEKEND_STATUS])->get();
            foreach ($attendanceDetails as $detail) {
                $remarks = null;
                $isHalfDayWeekendAbsent = (
                    $detail->attendance_status_id == HALF_DAY_WEEKEND_STATUS
                    && is_null($detail->check_in_at)
                    && is_null($detail->check_out_at)
                );

                $isCreatedStatus = $detail->attendance_status_id == CREATED_STATUS;

                if ($isHalfDayWeekendAbsent || $isCreatedStatus) {
                    $remarks = 'Marked as absent for ' . now()->format('Y-m-d') .
                        ' as he/she didn’t clock-in for the day (System generated).';
                }
                // $remarks = 'Marked absent on ' . now()->toDateTimeString() . ' as he/she didn`t clocked-in for the day (System generated).';
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
