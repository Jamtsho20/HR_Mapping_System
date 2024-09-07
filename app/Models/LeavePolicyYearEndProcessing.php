<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyYearEndProcessing extends Model
{
    use HasFactory;
    protected $fillable = [
        'mas_leave_policy_id',
        'allow_carryover',
        'carryover_limit',
        'pay_at_year_end',
        'min_balance_required',
        'min_encashment_per_year',
        'carry_forward_to_el',
        'carry_forward_limit'
    ];

    public function masLeavePolicy()
    {
        return $this->belongsTo(MasLeavePolicy::class);
    }
}
