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

    public function approvingAuthority(){
        return $this->hasOne(ApprovingAuthority::class, 'id'); 
    }

    public function approver(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
