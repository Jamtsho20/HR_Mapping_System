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
                $salaries->details['basic_pay'] ?? '-',
                $salaries->details['allowances']['House Allowance'] ?? '-',
                $salaries->details['allowances']['Critical Allowance'] ?? '-',
                $salaries->details['allowances']['Medical Allowance'] ?? '-',
                $salaries->details['deductions']['Salary Tax'] ?? '-',
                $salaries->details['allowances']['Add. Work Allowance'] ?? '-',
                $salaries->details['allowances']['Cash Allowance'] ?? '-',
                $salaries->details['deductions']['PF Contr'] ?? '-',
                $salaries->details['deductions']['H/Tax'] ?? '-',
                $salaries->details['deductions']['GSLI'] ?? '-',
                $salaries->details['deductions']['SIFA'] ?? '-',
                $salaries->details['deductions']['Loan BOB'] ?? '-',
                $salaries->details['deductions']['Loan BNB'] ?? '-',
                $salaries->details['deductions']['Loan TBank'] ?? '-',
                $salaries->details['deductions']['Loan BDFC'] ?? '-',
                $salaries->details['allowances']['Corporate Allowance'] ?? '-',
                $salaries->details['deductions']['SSSS'] ?? '-',
                $salaries->details['deductions']['Adv. Salary'] ?? '-',
                $salaries->details['deductions']['Loan NPPF'] ?? '-',
                $salaries->details['deductions']['Samsung Ded'] ?? '-',
                $salaries->details['deductions']['Loan RICB'] ?? '-',
                $salaries->details['deductions']['Loan DPNB'] ?? '-',
                $salaries->details['deductions']['Loan SIFA'] ?? '-',
                $salaries->details['allowances']['Difficulty Allowance'] ?? '-',
                $salaries->details['deductions']['Adv. Staff'] ?? '-',
                $salaries->details['gross_pay'] ?? '-',
                $salaries->details['net_pay'] ?? '-',
                $salaries->employee->empJob->empType->name ?? '-',
                $salaries->employee->empJob->designation->name ?? '-',
                $salaries->employee->empJob->department->name ?? '-',
                $salaries->employee->empJob->office->name ?? '-',
                $salaries->employee->empJob->account_number ?? '-',
                $salaries->employee->empJob->salary_disbursement_mode == 1 ? 'Cash' : 'Cheque' ?? '-',
                $salaries->employee->empJob->bank ?? '-',
                $salaries->employee->empJob->gradeStep->name ?? '-',
                $salaries->employee->date_of_appointment ? \Carbon\Carbon::parse($salaries->employee->date_of_appointment)->format('d-M-y') : '-',
                $salaries->employee->cid_no ?? '-',
                $salaries->employee->gender == 1 ? 'Male' : 'Female' ?? '-',
                $salaries->employee->dob ? \Carbon\Carbon::parse($salaries->employee->dob)->format('d-M-y') : '-',
                $salaries->employee->birth_place ?? '-',
                $salaries->for_month ? \Carbon\Carbon::parse($salaries->for_month)->format('M-y') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
            'Employee Name',
            'Basic Pay',
            'House ALL',
            'Critical ALL',
            'Medical ALL',
            'TDS',
            'ADD. Work ALL.',
            'Cash ALL.',
            'PF',
            'H/Tax',
            'GIS',
            'SIFA',
            'Loan BOB',
            'Loan BNB',
            'Loan TBank',
            'Loan BDFC',
            'Coporate ALL',
            'SSS',
            'Adv. Salary',
            'Loan NPPF',
            'Samsung',
            'Loan RICB',
            'Loan DPNB',
            'Loan SIFA',
            'DIFF ALL',
            'Adv. Staff',
            'Gross Earning',
            'Net Pay',
            'Job Nature',
            'Job Title',
            'Department',
            'Work Location',
            'Account Number',
            'Pay Method',
            'Bank Name',
            'Grade',
            'DOA',
            'CID',
            'Gender',
            'DOB',
            'Birth Place',
            'Salary Month',

        ];
    }
}
