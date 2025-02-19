<?php

namespace App\Exports;


use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryPaySlipExport implements FromCollection, WithHeadings
{
    protected $request;

    // Constructor to inject the Request
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $serialNo = 1;

        // Access the request data to apply filters
        return FinalPaySlip::filter($this->request)->get()->map(function ($salaries) use (&$serialNo) {
            return [
                $serialNo++,
                $salaries->employee->name,
                $salaries->employee->empJob->designation->name,
                $salaries->employee->empJob->empType->name,
                $salaries->for_month,
                $salaries->details['basic_pay'] ?? '0',
                $salaries->details['allowances']['House ALL'] ?? '0',
                $salaries->details['allowances']['Medical ALL'] ?? '0',
                $salaries->details['allowances']['Overtime ALL'] ?? '0',
                $salaries->details['allowances']['Corporate ALL'] ?? '0',
                $salaries->details['allowances']['Difficulty ALL'] ?? '0',
                $salaries->details['allowances']['Critical ALL'] ?? '0',
                $salaries->details['gross_pay'] ?? 0,
                $salaries->details['deductions']['Device EMI'] ?? '0',
                $salaries->details['deductions']['GSLI'] ?? '0',
                $salaries->details['deductions']['BOB_Loan'] ?? '0',
                $salaries->details['deductions']['TBank_Loan'] ?? '0',
                $salaries->details['deductions']['PF'] ?? '0',
                $salaries->details['deductions']['SIFA'] ?? '0',
                $salaries->details['deductions']['TDS'] ?? '0',
                $salaries->details['deductions']['H/Tax'] ?? '0',
                $salaries->details['net_pay'] ?? '0',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Job Title',
            'Job Nature',
            'Salary Month',
            'Basic Pay',
            'House ALL',
            'Medical ALL',
            'ADD. Work ALL.',
            'Coporate ALL',
            'DIFF ALL',
            'Critical ALL',
            'Gross Earning',
            'Samsung',
            'GIS',
            'BOB Loan',
            'Tbank Loan',
            'PF',
            'SIFA',
            'TDS',
            'H/Tax',
            'Net Pay',
        ];
    }
}
