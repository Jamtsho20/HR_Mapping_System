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
                $salaries->employee->username ?? '-',
                $salaries->employee->name ?? '-',
                $salaries->employee->empJob->gradeStep->name ?? '-',
                $salaries->employee->cid_no ?? '-',
                $salaries->employee->gender == 1 ? 'Male' : 'Female' ?? '-',
                $salaries->employee->dob ?? '-',
                $salaries->employee->birth_place ?? '-',
                $salaries->employee->empJob->designation->name ?? '-',
                $salaries->employee->empJob->department->name ?? '-',
                $salaries->employee->empJob->office->name ?? '-',
                $salaries->employee->empJob->empType->name ?? '-',
                $salaries->employee->date_of_appointment ?? '-',
                $salaries->employee->empJob->account_number ?? '-',
                $salaries->employee->empJob->salary_disbursement_mode == 1 ? 'Cash' : 'Cheque' ?? '-',
                $salaries->employee->empJob->bank ?? '-',
                $salaries->for_month ? \Carbon\Carbon::parse($salaries->for_month)->format('F Y') : '-',
                $salaries->details['basic_pay'] ?? '0',
                $salaries->details['allowances']['House Allowance'] ?? '0',
                $salaries->details['allowances']['Medical Allowance'] ?? '0',
                $salaries->details['allowances']['Add. Work Allowance'] ?? '0',
                $salaries->details['allowances']['Corporate Allowance'] ?? '0',
                $salaries->details['allowances']['Difficulty Allowance'] ?? '0',
                $salaries->details['allowances']['Critical Allowance'] ?? '0',
                $salaries->details['gross_pay'] ?? 0,
                $salaries->details['deductions']['Samsung Ded'] ?? '0',
                $salaries->details['deductions']['GSLI'] ?? '0',
                $salary->details['deductions']['Loan BNB'] ?? '0',
                $salaries->details['deductions']['Loan NPPF'] ?? '0',
                $salaries->details['deductions']['Loan BDFC'] ?? '0',
                $salaries->details['deductions']['Loan RICB'] ?? '0',
                $salaries->details['deductions']['Loan DPNB'] ?? '0',
                $salaries->details['deductions']['Loan BOB'] ?? '0',
                $salaries->details['deductions']['Loan TBank'] ?? '0',
                $salaries->details['deductions']['Loan SIFA'] ?? '0',
                $salaries->details['deductions']['PF Contr'] ?? '0',
                $salaries->details['deductions']['SIFA'] ?? '0',
                $salaries->details['deductions']['SSSS'] ?? '0',
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
            'Grade',
            'CID',
            'Gender',
            'DOB',
            'Birth Place',
            'Job Title',
            'Department',
            'Work Location',
            'Job Nature',
            'DOA',
            'Account Number',
            'Pay Method',
            'Bank Name',
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
            'SSS',
            'TDS',
            'H/Tax',
            'Net Pay',
        ];
    }
}
