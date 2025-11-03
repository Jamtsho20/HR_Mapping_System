<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyTraining extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'my_trainings';

    protected $fillable = [
        'type_id',
        'training_nature_id',
        'Funding_type_id',
        'title',
        'country_id',
        'dzongkhag_id',
        'location',
        'institute',
        'start_date',
        'end_date',
    ];
    public function trainingType()
    {
        return $this->belongsTo(\App\Models\MasTrainingType::class, 'type_id');
    }

    public function trainingNature()
    {
        return $this->belongsTo(\App\Models\MasTrainingNature::class, 'training_nature_id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\MasCountry::class, 'country_id');
    }


}
