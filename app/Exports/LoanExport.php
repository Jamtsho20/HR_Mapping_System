<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanExport implements FromCollection, WithHeadings
{
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
        return
            FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id')
            ->join('mas_loan_types', 'loan_e_m_i_deductions.loan_type_id', '=', 'mas_loan_types.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->join('mas_employees', 'loan_e_m_i_deductions.mas_employee_id', '=', 'mas_employees.id')
            ->where('mas_employees.is_active', 1)
            ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [17, 18, 19, 20, 21, 22, 23, 24])
            ->where('loan_e_m_i_deductions.is_paid_off', 0)
            ->filter($this->request) // Apply the filters
            ->select('final_pay_slips.*', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name', 'mas_loan_types.name as loan_type')->get()->map(function ($loans) use (&$serialNo) {
                return [
                    $serialNo++,
                    $loans->employee->name,
                    $loans->pay_head_name,
                    $loans->branch_code,
                    $loans->loan_number,
                    $loans->loan_type,
                    $loans->amount,
                    \Carbon\Carbon::parse($loans->for_month)->format('F Y')

                ];
            });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Bank',
            'Branch Code',
            'Loan Number',
            'Loan Type',
            'Monthly Installment',
            'Date',

        ];
    }
}
