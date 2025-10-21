<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasShiftType extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'name',
        'start_time',
        'end_time'
    ];

    public function departmentShifts()
    {
        return $this->hasMany(DepartmentWiseShift::class, 'type_id');
    }

    public function dailyAttendanceShift()
    {
        return $this->hasMany(AttendanceDetail::class, 'shift_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }

    public function getFormattedStartTimeAttribute(){
        $startTime = $this->start_time 
            ? \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('h:i A') 
            : null;
        return trim($startTime ?? '');
    }

    public function getFormattedEndTimeAttribute(){
        $endTime = $this->end_time 
            ? \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('h:i A') 
            : null;
        return trim($endTime ?? '');
    }
}
