<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasLeavePolicy extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = ['mas_leave_type_id','name','description','is_information_only','start_date','end_date','status']; 


    //realtions
    public function leaveType(){
        return $this->belongsTo(MasLeaveType::class, 'mas_leave_type_id');
    }

    public function leavePolicyPlan(){
        return $this->hasOne(LeavePolicyPlan::class, 'mas_leave_policy_id');
    }
    public function yearEnd()
    {
        return $this->hasOne(LeavePolicyYearEndProcessing::class, 'mas_leave_policy_id');
    }
    


    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('code') && $request->query('code') != '') {
            $query->where('code', 'LIKE', '%' . $request->query('code') . '%');
        }
    }
    // accessors & mutators
}
