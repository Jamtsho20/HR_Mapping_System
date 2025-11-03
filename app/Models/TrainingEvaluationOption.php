<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEvaluationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'option_text',
        'sequence',
    ];

    public function evaluation()
    {
        return $this->belongsTo(TrainingEvaluation::class, 'evaluation_id');
    }
}
