<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class PaySlip extends Model
{
    use HasFactory, CreatedByTrait;

    public function details()
    {
        return $this->hasMany(PaySlipDetail::class, 'pay_slip_id');
    }

    /**
     * Set the for_month attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setForMonthAttribute($value)
    {
        $this->attributes['for_month'] = Carbon::parse($value)->format('Y-m-01');
    }

    /**
     * Get the for_month attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function getStatusAttribute($value)
    {
        $statuses = [
            0 => 'Cancelled',
            1 => 'New',
            2 => 'Processed',
            3 => 'Verified',
            4 => 'Approved',
        ];

        return ['key' => $value, 'label' => $statuses[$value] ?? 'Unknown'];
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('for_month') && $request->query('for_month') != '') {
            $query->where('for_month', $request->query('for_month'));
        }
    }

    // public function repaymentSchedule($employeeID)
    // {
    //     $employeeIDs = json_decode($employeeID);
    //     //dd($employeeIDs);
    //     $currentMonth = Carbon::now()->format('Y-m-d');
    //     $sifaLoanApplicaitons = AdvanceApplication::whereIn('created_by', $employeeIDs)->whereIn('type_id', [7, 4])->get();
    //     foreach ($sifaLoanApplicaitons as $advanceApplication) {
    //         // Check if this application is still active (not paid off)
    //         $isActiveLoan = DB::table('loan_e_m_i_deductions')
    //             ->where('advance_application_id', $advanceApplication->id)
    //             ->where('is_paid_off', 0)
    //             // ->whereRaw("DATE_FORMAT(updated_at, '%Y-%m') = ?", [$currentMonth])
    //             ->first();
    //         // If the loan is fully paid, skip insertion
    //         if (!$isActiveLoan) {
    //             continue;
    //         }
    //         // dd($advanceApplication->id, $isActiveLoan);
    //         $latestRepayment = SifaLoanRepayment::where('advance_application_id', $advanceApplication->id)
    //             ->latest()
    //             ->first();

    //         $principal = $advanceApplication->amount;
    //         $openingBalance = $latestRepayment ? $latestRepayment->closing_balance : $principal;
    //         $interestRate = $advanceApplication->interest_rate; // annual
    //         $monthlyEmi = $advanceApplication->monthly_emi_amount;
    //         $startMonth = \Carbon\Carbon::now()->format('Y-m-d');

    //         $interestCharged = ($openingBalance * $interestRate) / (12 * 100);
    //         // $principalRepaid = $advanceApplication->type_id == 7 ? ($monthlyEmi - $interestCharged) : $isActiveLoan->amount;
    //         $principalRepaid = ($isActiveLoan && $isActiveLoan->amount == $monthlyEmi)
    //             ? ($monthlyEmi - $interestCharged)
    //             : ($isActiveLoan ? $isActiveLoan->amount : 0);

    //         // dd($principalRepaid);
    //         $closingBalance = max(0, $openingBalance - $principalRepaid);

    //         $percentageOutstanding = 0;
    //         if ($openingBalance !== 0.0 || $closingBalance !== 0.0) {
    //             $percentageOutstanding = ($closingBalance / $principal) * 100;
    //         }

    //         DB::table('sifaloanrepayment')->insert([
    //             'advance_application_id' => $advanceApplication->id,
    //             'type_id' => $advanceApplication->type_id,
    //             'repayment_number' => $latestRepayment ? $latestRepayment->repayment_number + 1 : 1,
    //             'month' => $startMonth,
    //             'opening_balance' => round($openingBalance, 2),
    //             'monthly_emi_amount' => round($monthlyEmi, 2),
    //             'interest_charged' => round($interestCharged, 2),
    //             'principal_repaid' => round($principalRepaid, 2),
    //             'closing_balance' => round($closingBalance, 2),
    //             'percentage_outstanding' => round($percentageOutstanding, 2),
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::updated(function ($paySlip) {
    //         if ($paySlip->isDirty('status') && $paySlip->getRawOriginal('status') != -1 && $paySlip->status['key'] == -1) {
    //             $details = $paySlip->details()
    //                 ->whereIn('mas_pay_head_id', [SIFA_LOAN_PAY_HEAD, 16])
    //                 ->where('amount', '!=', 0)
    //                 ->get();

    //             $employeeIDs = [];

    //             foreach ($details as $detail) {
    //                 $employeeIDs[] = $detail->mas_employee_id;
    //             }

    //             $paySlip->repaymentSchedule(json_encode($employeeIDs));
    //         }
    //     });
    // }
}
