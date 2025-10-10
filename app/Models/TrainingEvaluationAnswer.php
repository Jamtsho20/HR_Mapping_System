<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingEvaluation;
use App\Models\User;

class TrainingEvaluationAnswer extends Model
{
    use HasFactory;

    protected $table = 'training_evaluation_answers';

    protected $fillable = [
        'evaluation_id',
        'answer',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationship: Answer belongs to an Evaluation
     */
    public function evaluation()
    {
        return $this->belongsTo(TrainingEvaluation::class, 'evaluation_id');
    }

    /**
     * Created by employee
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function creator() // alias for blade
    {
        return $this->createdBy();
    }

    /**
     * Updated by employee
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function updater() // alias for blade
    {
        return $this->updatedBy();
    }
}
