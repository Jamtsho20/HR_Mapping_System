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
        return FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [11])
            ->where('loan_e_m_i_deductions.is_paid_off', 0)
            ->filter($this->request) // Apply the filters
            ->select('final_pay_slips.for_month', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name') // Select the columns you need, including pay_head name
            ->get()->map(function ($loans) use (&$serialNo) {
                return [
                    $serialNo++,
                    $loans->employee->name,
                    $loans->pay_head_name,
                    $loans->loan_number,
                    $loans->amount,
                    $loans->for_month,
                ];
            });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Loan Type',
            'Loan Number',
            'Monthly Installment',
            'Date'
        ];
    }
}
