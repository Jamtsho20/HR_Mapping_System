<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEMIDeduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'mas_pay_head_id', 'mas_employee_id', 'start_date', 'end_date', 'amount', 'loan_number',   'loan_type_id',  'recurring', 'recurring_months', 'remark', 'is_paid_of'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function payHead()
    {
        return $this->belongsTo(MasPayHead::class, 'mas_pay_head_id');
    }

    public function loanType() {
        return $this->belongsTo(MasLoanType::class, 'loan_type_id', 'id');
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('payhead') && $request->query('payhead') != '') {
            $query->where('mas_pay_head_id', $request->query('payhead'));
        }

        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('mas_employee_id', $request->query('employee'));
        }

        if ($request->has('loantype') && $request->query('loantype') != '') {
            $query->where('loan_type_id', $request->query('loantype'));
        }
    }
}
