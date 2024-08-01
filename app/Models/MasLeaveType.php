<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasLeaveType extends Model
{
    use HasFactory, CreatedByTrait;
    public function scopeFilter($query, $request)
    {
        if ($request->has('leave_type') && $request->query('leave_type') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('leave_type') . '%');
        }
    }

    public function leaveType()
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }
}
