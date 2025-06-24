<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['for_month','created_by','updated_by'];

    public function details()
    {
        return $this->hasMany(EmployeeAttendanceDetail::class, 'attendance_id');
    }

    public function dailyAttendances(){
        return $this->hasMany(DailyAttendance::class, 'attendance_id');
    }

    // Scope to filter by year
    public function scopeYear($query, $year)
    {
        if ($year) {
            $query->where('for_month', 'LIKE', '%-' . $year);
        }
    }

    // Scope to filter by for_month (e.g., 05-2025)
    public function scopeForMonth($query, $monthYear)
    {
        if ($monthYear) {
            $query->where('for_month', $monthYear);
        }
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true){

    }
}
