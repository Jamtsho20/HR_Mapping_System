<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SamsungDeductionExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    protected $deductions;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($request, $deductions)
    {
        $this->request = $request;
        $this->deductions = $deductions;
    }

    public function collection()
    {
        $serialNo = 1;

        return $this->deductions->map(function ($loans) use (&$serialNo) {
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
                formatAmount($loans->emi_amount, false),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            // 'Applied On',
            'Employee Name',
            'Employee ID',
            // 'Designation',
            // 'Department',
            // 'Region',
            // 'Office Location',
            'Loan Type',
            'Loan Number',
            // 'Item Type/Device Code',
            'Start Date',
            'End Date',
            'No of Installments (Months)',
            'For Month',
            'Monthly Installment (Nu.)',
            // 'Installment Paid (Nu.)',
            // 'Approved By',
            // 'Approved On'
        ];
    }
}
