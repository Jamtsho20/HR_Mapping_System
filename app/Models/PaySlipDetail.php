<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlipDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "pay_slip_id",
        "mas_employee_id",
        "mas_pay_head_id",
        "amount",
        "created_by",
        "edited_by",
    ];
    
    public function paySlip() {
        return $this->belongsTo(PaySlip::class,'pay_slip_id');
    }
    public function employee() {
        return $this->belongsTo(User::class,'employee_id');
    }
    public function payHead() {
        return $this->belongsTo(MasPayHead::class,'pay_head_id');
    }
}
