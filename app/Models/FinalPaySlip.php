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
        //specify table name final_pay_slip for loan report where tables are joined
        if ($request->has('employee_id') && $request->get('employee_id')) {
            $query->where('final_pay_slips.mas_employee_id', $request->get('employee_id'));
        }
        
        if ($request->has('mas_pay_head_id') && $request->get('mas_pay_head_id')) {
            $query->where('loan_e_m_i_deductions.mas_pay_head_id', $request->get('mas_pay_head_id'));
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
