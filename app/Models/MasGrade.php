<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasGrade extends Model
{
    use HasFactory, CreatedByTrait;

    //relationships
    public function gradeSteps()
    {
        return $this->hasMany(MasGradeStep::class, 'mas_grade_id');
    }

    // filter
    public function scopeFilter($query, $request)
    {
        if ($request->has('grade_name') && $request->query('grade_name') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('grade_name') . '%');
        }
    }
    public function payGroupDetails()
    {
    return $this->hasMany(MasPayGroupDetail::class, 'mas_grade_id');
    }

}
