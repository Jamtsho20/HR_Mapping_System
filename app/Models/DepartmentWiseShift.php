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

    public function deptShiftEmployees(){
        return $this->hasMany(EmployeeShift::class, 'department_shift_id');
    }
    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
