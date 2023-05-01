<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasQualification extends Model
{
    use HasFactory, CreatedByTrait;
    
    public function scopeFilter($query, $request)
    {
        if ($request->has('qualification') && $request->query('qualification') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('qualification') . '%');
        }
    }
}
