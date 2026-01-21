<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MRF extends Model
{
    use HasFactory;

    protected $table = 'mrf';

    protected $fillable = [
        'requisition_number',
        'date_of_requisition',
        'mas_function_id',
        'mas_department_id',
        'mas_section_id',
        'designation_id',
        'employment_type_id',
        'location',
        'experience',
        'vacancies',
        'mas_grade_step_id',
        'mrf_type',
        'job_description',
        'reason',
        'remarks',
        'requested_by',
        'status',
        'approved_by',
        'approved_at',
    ];
    protected $casts = [
        'date_of_requisition' => 'datetime',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // Function relationship
    public function function()
    {
        return $this->belongsTo(FunctionModel::class, 'mas_function_id');
    }
    public function department()
    {
        return $this->belongsTo(MasDepartment::class, 'mas_department_id');
    }
    public function section()
    {
        return $this->belongsTo(MasSection::class, 'mas_section_id');
    }
    public function designation()
    {
        return $this->belongsTo(MasDesignation::class, 'designation_id');
    }
    public function employmentType()
    {
        return $this->belongsTo(MasEmploymentType::class, 'employment_type_id');
    }
    public function scopeHrApproved($query)
    {
        return $query->where('status', 'hr_approved');
    }

    public function scopePendingForHr($query)
    {
        return $query->where('status', 'hod_submitted');
    }

    public function scopePendingForAdmin($query)
    {
        return $query->where('status', 'hr_approved');
    }
    // User who requested the MRF
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function gradeStep()
    {
        return $this->belongsTo(MasGradeStep::class, 'mas_grade_step_id');
    }

    // User who approved the MRF
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
