<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlipSummary extends Model
{
    use HasFactory;

    protected $table = 'pay_slip_summary_view';

    public function payHead() {
        return $this->belongsTo(MasPayHead::class, 'payhead_id');
    }
}
