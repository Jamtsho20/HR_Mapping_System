<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SamsungDeductionExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $serialNo = 1;
        return FinalPaySlip::leftJoin('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->where('loan_e_m_i_deductions.mas_pay_head_id', 16)
            ->where('loan_e_m_i_deductions.is_paid_off', 0)
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.start_date, '%Y-%m-%d') <= ?", [now()->format('Y-m-01')])
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.end_date, '%Y-%m-%d') >= ?", [now()->format('Y-m-01')])  // Compare Year-Month
            ->filter($this->request) // Apply the filters
            ->selectRaw('final_pay_slips.for_month, loan_e_m_i_deductions.*, mas_pay_heads.name as pay_head_name') // Select the columns you need, including pay_head name
            ->get()->map(function ($loans) use (&$serialNo) {
                return [
                    $serialNo++,
                    $loans->employee->emp_name,
                    $loans->employee->username,
                    $loans->pay_head_name,
                    $loans->loan_number,
                    getDisplayDateFormat($loans->start_date),
                    getDisplayDateFormat($loans->end_date),
                    $loans->recurring_months,
                    \Carbon\Carbon::parse($loans->for_month)->format('F Y'),
                    formatAmount($loans->amount, false),
                ];
            });
    }
                                                        
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Employee ID',
            'Loan Type',
            'Loan Number',
            'Start Date',
            'End Date',
            'No of Installments (Months)',
            'For Month',
            'Monthly Installment (Nu.)'
        ];
    }
}
