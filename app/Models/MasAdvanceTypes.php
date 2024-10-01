<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasAdvanceTypes extends Model
{
    use HasFactory,CreatedByTrait;

    public function scopeFilter($query, $request)
    {
        if ($request->has('advancetype') && $request->query('advancetype') != '') {
            $query->where('advancetype', 'LIKE', '%' . $request->query('advancetype') . '%');
        }
        if ($request->has('code') && $request->query('code') != '') {
            $query->where('code', 'LIKE', '%' . $request->query('code') . '%');
        }

        return $query;
    }
    public function advanceApplications()
    {
        return $this->hasMany(AdvanceApplication::class, 'advance_type', 'id');
    }

}
