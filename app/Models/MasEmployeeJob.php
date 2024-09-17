<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id', 'mas_grade_id', 'mas_grade_step_id',
        'mas_employment_type_id', 'immediate_supervisor', 'job_location', 'basic_pay', 'bank', 'account_number', 'pf_number', 'tpn_number'
    ];

    public function masEmployee() {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function department() {
        return $this->belongsTo(MasDepartment::class, 'mas_department_id');
    }

    public function section() {
        return $this->belongsTo(MasSection::class, 'mas_section_id');
    }
    public function designation() {
        return $this->belongsTo(MasDesignation::class, 'mas_designation_id');
    }

    public function grade() {
        return $this->belongsTo(MasGrade::class, 'mas_grade_id');
    }

    public function gradeStep() {
        return $this->belongsTo(MasGradeStep::class, 'mas_grade_step_id');
    }

    public function empType() {
        return $this->belongsTo(MasEmploymentType::class, 'mas_employment_type_id');
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'immediate_supervisor');
    }
}
