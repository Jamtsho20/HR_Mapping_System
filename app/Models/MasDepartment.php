<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasDepartment extends Model
{
    use HasFactory, CreatedByTrait;

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('department') && $request->query('department') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('department') . '%');
        }
    }
}
