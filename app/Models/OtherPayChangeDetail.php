<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPayChangeDetail extends Model
{
    use HasFactory, CreatedByTrait;

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function gradeStep()
    {
        return $this->belongsTo(MasGradeStep::class, 'mas_grade_step_id');
    }
    public function otherPayChange()
    {
        return $this->belongsTo(OtherPayChange::class, 'other_pay_change_id');
    }
}
