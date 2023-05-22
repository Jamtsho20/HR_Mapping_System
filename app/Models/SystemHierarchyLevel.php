<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemHierarchyLevel extends Model
{
    use HasFactory,CreatedByTrait;
    protected $guarded = ['id'];

    //relationships
    public function hierarchy()
    {
        return $this->belongsTo(SystemHierarchy::class, 'system_hierarchy_id');
    }
}
