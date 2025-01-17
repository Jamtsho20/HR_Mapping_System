<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovingAuthority extends Model
{
    use HasFactory, CreatedByTrait;

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hierarchyLevel(){
        return $this->hasMany(SystemHierarchyLevel::class, 'approving_authority_id');
    }
}
