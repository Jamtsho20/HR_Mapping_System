<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'attendance_id', 'department_id', 'section_id', 'day', 'status', 'created_by'
    ];

    public function empAttendance(){
        return $this->belongsTo(EmployeeAttendance::class, 'attendance_id');
    }

    public function details(){
        return $this->hasMany(AttendanceDetail::class, 'daily_attendance_id');
    }
}
