<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyYearEndProcessing extends Model
{
    use HasFactory;

    public function masLeavePolicy() {
        return $this->belongsTo(MasLeavePolicy::class);
    }
}
