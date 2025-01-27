<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyRule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'leave_policy_plan_id',
        'mas_grade_step_id',
        'uom',
        'duration',
        'start_date',
        'end_date',
        'is_loss_of_pay',
        'mas_employment_type_id',
        'status',
    ];

    public function leavePolicyPlan()
    {
        return $this->belongsTo(LeavePolicyPlan::class);
    }
    public function gradeStep()
    {
        return $this->hasOne(MasGradeStep::class, 'id');
    }
}
