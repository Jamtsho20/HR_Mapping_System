<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEMIDeductionLog extends Model
{
    use HasFactory;

    public function loanDeduction(){
        return $this->belongsTo(LoanEMIDeduction::class, 'loan_id');
    }
}
