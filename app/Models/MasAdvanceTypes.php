<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasAdvanceTypes extends Model
{
    use HasFactory,CreatedByTrait;

    //relationship

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }
    
    public function advanceApplications()
    {
        return $this->hasMany(AdvanceApplication::class, 'type_id');
    }

    // scope filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
        if ($request->has('code') && $request->query('code') != '') {
            $query->where('code', 'LIKE', '%' . $request->query('code') . '%');
        }

        return $query;
    }

    //accessors and mutators

}
