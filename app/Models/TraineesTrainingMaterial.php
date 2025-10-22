<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineesTrainingMaterial extends Model
{
    use HasFactory;

    protected $table = 'trainees_training_materials';
    protected $casts = [
        'attachment' => 'array',
        'owner_ship' => 'array',
    ];

    protected $fillable = [
        'trainee_list_id',
        'attachment',
        'owner_ship',
        'description',
    ];
}
