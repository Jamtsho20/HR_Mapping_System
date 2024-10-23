<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasLeaveType extends Model
{
    use HasFactory, CreatedByTrait;

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function empLeave(){
        return $this->hasMany(EmployeeLeave::class, 'Mas_leave_type_id');
    }

    public function leaveApplications(){
        return $this->hasMany(LeaveApplication::class, 'mas_leave_type_id');
    }

    public function leavePolicy(){
        return $this->hasOne(MasLeavePolicy::class, 'mas_leave_type_id');
    }

    //scope filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('leave_type') && $request->query('leave_type') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('leave_type') . '%');
        }
    }

    // accessors or mutators
    

}
