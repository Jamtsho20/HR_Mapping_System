<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyPlan extends Model
{
    use HasFactory;
        protected $fillable = ['mas_leave_policy_id','mas_leave_type_id','gender','attachment_required','leave_year','credit_frequency','credit','leave_limits','can_avail_in']; 


    protected $cast = [
        'can_avail_in' => 'array',
        'leave_limits' => 'array'
    ];

    public function masLeavePolicy(){
        return $this->belongsTo(MasLeavePolicy::class);
    }
    public function LeavePolicyRule()
    {
        return $this->hasMany(LeavePolicyRule::class);
    }

}
