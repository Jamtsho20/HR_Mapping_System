<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemHierarchy extends Model
{
    use HasFactory;
    
    public function scopeFilter($query, $request)
    {
        if ($request->has('hierarchy_name') && $request->query('hierarchy_name') != '') {
            $query->where('hierarchy_name', 'LIKE', '%' . $request->query('hierarchy_name') . '%');
        }
    }
}


