<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEMIDeduction extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function payHead()
    {
        return $this->belongsTo(MasPayHead::class, 'mas_pay_head_id');
    }
}
