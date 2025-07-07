<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['daily_attendance_id', 'employee_id', 'check_in_at', 'attendance_status_id', 'check_out_at', 'check_in_ip', 'check_out_ip', 'check_in_office_id', 'check_out_office_id', 'created_by', 'updated_by', 'update_history'];

    public function dailyAttendance()
    {
        return $this->belongsTo(DailyAttendance::class, 'daily_attendance_id');
    }

    public function attendanceStatus()
    {
        return $this->belongsTo(AttendanceStatus::class, 'attendance_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function checkedInFrom(){
        return $this->belongsTo(MasOffice::class, 'check_in_office_id');
    }

    public function checkedOutFrom(){
        return $this->belongsTo(MasOffice::class, 'check_out_office_id');
    }

    public function scopeFilter($query, $request)
    {

        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('mas_employee_id', $request->query('employee'));
        }
    }
}
