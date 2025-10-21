<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceDetail extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['daily_attendance_id', 'employee_id', 'shift_id', 'check_in_at', 'attendance_status_id', 'check_out_at', 'check_in_ip', 'check_out_ip', 'check_in_office_id', 'check_out_office_id', 'check_in_from', 'check_out_from', 'check_in_coordinates', 'check_out_coordinates', 'verified_by', 'approved_by', 'created_by', 'updated_by', 'update_history'];

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

    public function shift()
    {
        return $this->belongsTo(MasShiftType::class, 'shift_id');
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

    // if attendance status is informed late then in report for display show present
    public function getPresentDisplayStatusAttribute()
    {
        return match ($this->attendance_status_id) {
            INFORMED_LATE_STATUS => 'P'
        };
    }
// if attendance status is informed late then in report for display show present color
    public function getPresentStatusColorAttribute()
    {
        return match ($this->attendance_status_id) {
            INFORMED_LATE_STATUS => '#2ec158'
        };
    }
// if attendance status is informed late then in report for display show present description
    public function getPresentStatusDescriptionAttribute()
    {
        return match ($this->attendance_status_id) {
            INFORMED_LATE_STATUS => 'Present'
        };
    }

    public function getFormattedCheckInAtAttribute()
    {
        return $this->check_in_at
            ? Carbon::createFromFormat('H:i:s', $this->check_in_at)->format('g:i:s A')
            : null;
    }

    public function getFormattedCheckOutAtAttribute()
    {
        // return $this->check_out_at
        //     ? Carbon::createFromFormat('H:i:s', $this->check_out_at)->format('g:i A')
        //     : null;
        //uncomment below one if se4cond is required
        return $this->check_out_at
            ? Carbon::createFromFormat('H:i:s', $this->check_out_at)->format('g:i:s A')
            : null;
    }
}
