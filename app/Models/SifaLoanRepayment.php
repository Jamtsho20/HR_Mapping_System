<?php

namespace App\Models;

use Carbon\Carbon;
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
            $query->where('mas_employee_id', $request->get('employee_id'));
        }


     

        // Add more filters here if needed
        return $query;
    }
    
    
}
