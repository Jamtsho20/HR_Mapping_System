<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovingAuthority extends Model
{
    use HasFactory, CreatedByTrait;

    public function hierarchyLevel(){
        return $this->belongsTo(SystemHierarchyLevel::class, 'approving_authority_id');
    }
}
