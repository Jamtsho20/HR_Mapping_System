<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentWiseShift extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'name', 'type_id', 'department_id', 'start_time', 'end_time', 'status', 'created_by', 'updated_by'
    ];

    public function department(){
        return $this->belongsTo(MasDepartment::class, 'department_id');
    }

    public function shiftType(){
        return $this->belongsTo(MasShiftType::class, 'type_id');
    }

    // public function deptShiftEmployees(){
    //     return $this->hasMany(EmployeeShift::class, 'department_shift_id');
    // }

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

    // public function getShiftTimeAttribute()
    // {
    //     $start = $this->start_time 
    //         ? \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('h:i A') 
    //         : null;

    //     $end = $this->end_time 
    //         ? \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('h:i A') 
    //         : null;

    //     return trim(($start ?? '') . ' - ' . ($end ?? ''), ' -');
    // }


    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
