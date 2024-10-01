<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceTypes extends Model
{
    use HasFactory,CreatedByTrait;

    public function approvableRule()
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('advancetype') && $request->query('advancetype') != '') {
            $query->where('advancetype', 'LIKE', '%' . $request->query('advancetype') . '%');
        }

        return $query;
    }

}
