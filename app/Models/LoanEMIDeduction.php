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
        if ($request->has('pay_head') && $request->query('pay_head') != '') {
            $query->where('pay_head', $request->query('pay_head'));
        }

        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('employee', 'LIKE', '%' . $request->query('employee') . '%');
        }
    }
}
