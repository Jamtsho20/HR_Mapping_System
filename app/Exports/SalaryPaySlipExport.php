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
        return FinalPaySlip::whereHas('employee', function ($q) {
            $q->where('is_active', 1);
        })->filter($this->request)->get()->map(function ($salaries) use (&$serialNo) {
            return [
                $serialNo++,
                $salaries->employee->username ?? '-',
                $salaries->employee->name ?? '-',
                formatAmount($salaries->details['basic_pay'], false) ?? '-',
                formatAmount($salaries->details['allowances']['House Allowance'], false) ?? '-',
                formatAmount($salaries->details['allowances']['Critical Allowance'], false) ?? '-',
                formatAmount($salaries->details['allowances']['Medical Allowance'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Salary Tax'], false) ?? '-',
                formatAmount($salaries->details['allowances']['Add. Work Allowance'], false) ?? '-',
                formatAmount($salaries->details['allowances']['Cash Allowance'], false) ?? '-',
                formatAmount($salaries->details['deductions']['PF Contr'], false) ?? '-',
                formatAmount($salaries->details['deductions']['H/Tax'], false) ?? '-',
                formatAmount($salaries->details['deductions']['GSLI'], false) ?? '-',
                formatAmount($salaries->details['deductions']['SIFA'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan BOB'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan BNB'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan TBank'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan BDFC'], false) ?? '-',
                formatAmount($salaries->details['allowances']['Corporate Allowance'], false) ?? '-',
                formatAmount($salaries->details['deductions']['SSSS'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Adv. Salary'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan NPPF'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Samsung Ded'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan RICB'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan DPNB'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Loan SIFA'], false) ?? '-',
                formatAmount($salaries->details['allowances']['Difficulty Allowance'], false) ?? '-',
                formatAmount($salaries->details['deductions']['Adv. Staff'], false) ?? '-',
                formatAmount($salaries->details['gross_pay'], false) ?? '-',
                formatAmount($salaries->details['net_pay'], false) ?? '-',
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
