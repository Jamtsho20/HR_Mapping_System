<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeJob extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id', 'mas_grade_id', 'mas_grade_step_id'];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function grade() {
        return $this->hasOne(MasGrade::class, 'id', 'mas_grade_id');
    }

    public function gradeStep() {
        return $this->hasOne(MasGradeStep::class, 'id', 'mas_grade_step_id');
    }

    public function designation() {
        return $this->hasOne(MasDesignation::class, 'id', 'mas_designation_id');
    }

    public function department() {
        return $this->hasOne(MasDepartment::class, 'id', 'mas_department_id');
    }
}
