<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineesTrainingMaterial extends Model
{
    use HasFactory,CreatedByTrait;

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
        'created_by',
        'updated_by',
    ];
     public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
