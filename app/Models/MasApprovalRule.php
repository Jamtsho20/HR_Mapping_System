<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasApprovalRule extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['mas_approval_head_id', 'name', 'start_date', 'end_date', 'is_active'];

    public function approvable(){
        return $this->morphTo();
    }

    public function approvalHead() {
        return $this->belongsTo(MasApprovalHead::class, 'mas_approval_head_id');
    }

    public function approvalConditions() {
        return $this->hasMany(MasApprovalCondition::class, 'mas_approval_rule_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('name') . '%');
        }
    }
}
