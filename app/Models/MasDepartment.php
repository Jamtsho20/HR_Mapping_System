<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasDepartment extends Model
{
    use HasFactory, CreatedByTrait;
    //relationship
    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function departmentHead()
    {
        return $this->belongsTo(User::class, 'mas_employee_id'); // Adjust the foreign key if needed
    }

    public function sections()
    {
        return $this->hasMany(MasSection::class, 'mas_department_id');
    }

    public function departmentWiseShifts()
    {
        return $this->hasMany(DepartmentWiseShift::class, 'department_id');
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('department') && $request->query('department') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('department') . '%');
        }
    }

    //accessors & mutators
    public function getCodeNameAttribute()
    {
        return $this->short_name . ' - ' . $this->name;
    }
}
