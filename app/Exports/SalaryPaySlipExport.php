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
                $salaries->employee->username,
                $salaries->employee->name,
                $salaries->employee->empJob->designation->name,
                $salaries->employee->empJob->empType->name,
                $salaries->for_month,
                $salaries->details['basic_pay'] ?? '0',
                $salaries->details['allowances']['House Allowance'] ?? '0',
                $salaries->details['allowances']['Medical Allowance'] ?? '0',
                $salaries->details['allowances']['Add. Work Allowance'] ?? '0',
                $salaries->details['allowances']['Corporate Allowance'] ?? '0',
                $salaries->details['allowances']['Difficulty Allowance'] ?? '0',
                $salaries->details['allowances']['Critical Allowance'] ?? '0',
                $salaries->details['gross_pay'] ?? 0,
                $salaries->details['deductions']['Device EMI'] ?? '0',
                $salaries->details['deductions']['GSLI'] ?? '0',
                $salary->details['deductions']['Loan BNB'] ?? '0',
                $salaries->details['deductions']['Loan NPPF'] ?? '0',
                $salaries->details['deductions']['Loan BDFC'] ?? '0',
                $salaries->details['deductions']['Loan RICB'] ?? '0',
                $salaries->details['deductions']['Loan DPNB'] ?? '0',
                $salaries->details['deductions']['Loan BOB'] ?? '0',
                $salaries->details['deductions']['Loan TBank'] ?? '0',
                $salaries->details['deductions']['Loan SIFA'] ?? '0',
                $salaries->details['deductions']['PF'] ?? '0',
                $salaries->details['deductions']['SIFA'] ?? '0',
                $salaries->details['deductions']['Salary Tax'] ?? '0',
                $salaries->details['deductions']['H/Tax'] ?? '0',
                $salaries->details['net_pay'] ?? '0',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
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
            'Loan BNB',
            'Loan NPPF',
            'Loan BDFC',
            'Loan RICB',
            'Loan DPNB',
            'Loan BOB',
            'Loan TBank',
            'Loan SIFA',
            'PF',
            'SIFA',
            'TDS',
            'H/Tax',
            'Net Pay',
        ];
    }
}
