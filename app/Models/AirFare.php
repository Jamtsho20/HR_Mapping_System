<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirFare extends Model
{
    use HasFactory;
    protected $table = 'air_fares';

    public function trainingApplication()
    {
        return $this->belongsTo(TrainingApplication::class, 'training_application_id');
    }
}
