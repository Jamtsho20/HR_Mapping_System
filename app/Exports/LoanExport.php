<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanExport implements FromCollection, WithHeadings
{
    protected $request;
    protected $loans;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($request, $loans)
    {
        $this->request = $request;
        $this->loans = $loans;
    }

    public function collection()
    {
        $serialNo = 1;
        return $this->loans->map(function ($loans) use (&$serialNo) {
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
