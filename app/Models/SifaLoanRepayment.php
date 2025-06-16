<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaLoanRepayment extends Model
{
    use HasFactory;
    protected $table = 'sifaloanrepayment';
    protected $fillable = [
        'advance_application_id',
        'repayment_number',
        'month',
        'opening_balance',
        'monthly_emi_amount',
        'interest_charged',
        'principal_repaid',
        'closing_balance',
        'percentage_outstanding',
    ];

    public function advanceApplication()
    {
        return $this->belongsTo(AdvanceApplication::class);
    }
    
    
}
