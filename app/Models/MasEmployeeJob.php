<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeJob extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id', 'mas_grade_id', 'mas_grade_step_id'];

    public function masEmployee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function masDepartment()
    {
        return $this->hasOne(MasDepartment::class, 'id');
    }
    public function masDesignation()
    {
        return $this->hasOne(MasDesignation::class, 'id');
    }
    public function masGrade()
    {
        return $this->hasOne(MasGrade::class, 'id');
    }
    public function masGradeStep()
    {
        return $this->hasOne(MasGradeStep::class, 'id', 'mas_grade_step_id');
    }
}
