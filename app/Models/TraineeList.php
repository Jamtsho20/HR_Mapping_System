<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineeList extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'trainee_lists';
    public function trainingApplication()
    {
        return $this->belongsTo(TrainingApplication::class, 'training_application_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
