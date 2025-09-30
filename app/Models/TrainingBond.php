<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingBond extends Model
{
    use HasFactory,CreatedByTrait;

    protected $table = 'training_bonds';

    protected $fillable = [
        'training_list_id',
        'start_date',
        'end_date',
        'attachment',
    ];


    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
