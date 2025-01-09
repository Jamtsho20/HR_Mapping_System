<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasResignationType extends Model
{
    use HasFactory, CreatedByTrait;
    
    public function scopeFilter($query, $request)
    {
        if ($request->has('resignation_type') && $request->query('resignation_type') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('resignation_type') . '%');
        }
    }
}
