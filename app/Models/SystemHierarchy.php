<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemHierarchy extends Model
{
    use HasFactory,CreatedByTrait;
    //relationships
    public function hierarchyLevels()
    {
        return $this->hasMany(SystemHierarchyLevel::class, 'system_hierarchy_id');
    }
    public function scopeFilter($query, $request)
    {
        if ($request->has('hierarchy_name') && $request->query('hierarchy_name') != '') {
            $query->where('hierarchy_name', 'LIKE', '%' . $request->query('hierarchy_name') . '%');
        }
    }
}


