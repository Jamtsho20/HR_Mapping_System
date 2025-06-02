<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalPaySlip extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'for_month', 'details'];

    public function scopeFilter($query, $request)
    {
        if ($request->get('year')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('year'));

            // Step 2: Convert each date to Y-m format using Carbon
            $startDate = Carbon::createFromFormat('Y-m', trim($dates[0]));

            // Extract year and month
            $year = $startDate->year;
            $month = $startDate->month;

            // Filter by year and month
            $query->whereYear('for_month', $year)
                ->whereMonth('for_month', $month);
        }

        if ($request->get('date')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('date'));

            // Step 2: Convert each date to Y-m-d format using Carbon
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->format('Y-m-d');

            // Step 3: Apply the date range filter
            if ($startDate === $endDate) {
                $query->whereDate('for_month', $startDate);
            } else {
                $query->whereBetween('for_month', [$startDate, $endDate]);
            }
        }
        //specify table name final_pay_slip for loan report where tables are joined
        if ($request->has('employee_id') && $request->get('employee_id')) {
            $query->where('final_pay_slips.mas_employee_id', $request->get('employee_id'));
        }
        if ($request->has('employee_id') && $request->get('employee_id')) {
            $query->where('final_pay_slips.mas_employee_id', $request->get('employee_id'));
        }

        if ($request->has('bank_location') && $request->get('bank_location')) {

            $query->whereHas('employee.empJob', function ($query) use ($request) {
                $query->where('bank', $request->get('bank_location')); // Changed 'bank' to 'bank_location'
            });
        }

        if ($request->has('mas_pay_head_id') && $request->get('mas_pay_head_id')) {
            $query->where('loan_e_m_i_deductions.mas_pay_head_id', $request->get('mas_pay_head_id'));
        }

        if ($request->has('cid_no') && $request->query('cid_no') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('cid_no', $request->query('cid_no'));
            });
        }

        // Add more filters here if needed
        return $query;
    }
    public function getDetailsAttribute()
    {
        return is_array($this->attributes['details']) ? $this->attributes['details'] : json_decode($this->attributes['details'], true);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
