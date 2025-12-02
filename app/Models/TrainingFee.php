<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingFee extends Model
{
    use HasFactory;
    protected $table = 'training_fees';

    public function trainingApplication()
    {
        return $this->belongsTo(TrainingApplication::class, 'training_application_id');
    }
}
