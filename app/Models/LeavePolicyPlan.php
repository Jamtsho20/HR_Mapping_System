<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyPlan extends Model
{
    use HasFactory;

    protected $cast = [
        'can_avail_in' => 'array',
        'leave_limits' => 'array'
    ];

    public function masLeavePolicy(){
        return $this->belongsTo(MasLeavePolicy::class);
    }

}
