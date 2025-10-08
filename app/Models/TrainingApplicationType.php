<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingApplicationType extends Model
{
    use HasFactory,CreatedByTrait;
    public function approvableRule() 
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function trainingApplication()
    {
        return $this->hasMany(TrainingApplication::class, 'training_application_id');
    }
}
