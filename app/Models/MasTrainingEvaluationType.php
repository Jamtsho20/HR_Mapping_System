<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasTrainingEvaluationType extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'mas_training_evaluation_types';

    protected $fillable = [
        'name',
    ];


    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
