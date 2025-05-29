<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasShiftType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'start_time', 'end_time'
    ];

    public function departmentShifts(){
        return $this->hasMany(DepartmentWiseShift::class, 'type_id');
    }
}
