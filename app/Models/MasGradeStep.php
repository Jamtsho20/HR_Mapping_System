<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasGradeStep extends Model
{
    use HasFactory, CreatedByTrait;

    protected $guarded = ['id'];

    //relationships
    public function grade()
    {
        return $this->belongsTo(MasGrade::class, 'mas_grade_id');
    }

    // accessors & mutators
    public function getPayScaleAttribute(){
        return $this->starting_salary . ' - ' . $this->increment . ' - ' . $this->ending_salary;
    }
}
