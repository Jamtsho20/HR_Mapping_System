<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;

class LoanEMIDeduction extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = [
        'mas_pay_head_id',
        'mas_employee_id',
        'start_date',
        'end_date',
        'amount',
        'loan_number',
        'loan_type_id',
        'recurring',
        'recurring_months',
        'remark',
        'is_paid_of',
        'advance_application_id',
        'paid_off_by',
        'paid_off_at',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function payHead()
    {
        return $this->belongsTo(MasPayHead::class, 'mas_pay_head_id');
    }

    public function loanType()
    {
        return $this->belongsTo(MasLoanType::class, 'loan_type_id', 'id');
    }

    public function advanceApplication()
    {
        return $this->belongsTo(AdvanceApplication::class, 'loan_number', 'transaction_no');
    }

    public function paySlip(){
        return $this->belongsTo(FinalPaySlip::class, 'mas_employee_id', 'mas_employee_id');
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
        if ($request->has('cid_no') && $request->query('cid_no') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('cid_no', '=', $request->query('cid_no'));
            });
        }
    }
}
