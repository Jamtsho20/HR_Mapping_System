<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlipDetail extends Model
{
    use HasFactory, CreatedByTrait;

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
        return $this->belongsTo(User::class,'mas_employee_id');
    }
    public function payHead() {
        return $this->belongsTo(MasPayHead::class,'mas_pay_head_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->filled('employee_id')) {
            $query->whereHas('employee', function($employeeQuery) use ($request) {
                $employeeQuery->where('id', $request->query('employee_id'));
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($employeeQuery) use ($request) {
                $employeeQuery->whereHas('empJob', function ($jobQuery) use ($request) {
                    $jobQuery->where('mas_department_id', $request->query('department'));
                });
            });
        }

        if ($request->filled('employment_type')) {
            $query->whereHas('employee', function($employeeQuery) use ($request) {
                $employeeQuery->whereHas('empJob', function ($jobQuery) use ($request) {
                    $jobQuery->where('mas_employment_type_id', $request->query('employment_type'));
                });
            });
        }

        if ($request->filled('payhead')) {
            $query->whereHas('payHead', function($payheadQuery) use ($request) {
                $payheadQuery->where('id', $request->query('payhead'));
            });
        }
    }
}
