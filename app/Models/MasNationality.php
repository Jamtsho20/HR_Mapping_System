<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasNationality extends Model
{
    use HasFactory, CreatedByTrait;
    
    public function scopeFilter($query, $request)
    {
        if ($request->has('nationality') && $request->query('nationality') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('nationality') . '%');
        }
    }
}
