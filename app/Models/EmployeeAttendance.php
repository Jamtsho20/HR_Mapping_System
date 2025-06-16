<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['for_month'];

    public function details()
    {
        return $this->hasMany(EmployeeAttendanceDetail::class, 'attendance_id');
    }

    public function dailyAttendances(){
        return $this->hasMany(DailyAttendance::class, 'attendance_id');
    }
}
