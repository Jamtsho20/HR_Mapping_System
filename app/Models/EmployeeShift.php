<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = [
        'mas_employee_id',
        // 'department_shift_id',
        'morning_shift_days',
        'evening_shift_days',
        'night_shift_days',
        'full_shift_days',
        'off_days'
    ];

    protected $cast = [
        'morning_shift_days' => 'array',
        'evening_shift_days' => 'array',
        'night_shift_days' => 'array',
        'full_shift_days' => 'array',
        'off_days' => 'array'
    ];

    public function masEmployee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    // public function departmentShift()
    // {
    //     return $this->belongsTo(DepartmentWiseShift::class, 'department_shift_id');
    // }

    public function scopeFilter($query, $request)
    {
        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('mas_employee_id', 'LIKE', '%' . $request->query('employee') . '%');
        }

        // if ($onesOwnRecord) {
        //     $query->where('mas_employee_id', auth()->user()->id);
        // }
        
    }

}
