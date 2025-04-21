<?php

namespace App\Exports;


use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryPaySlipExport implements FromCollectionWithHeadings
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
                (float)$salaries->details['basic_pay'] ?? '-',
                (float)$salaries->details['allowances']['House Allowance'] ?? '-',
                (float)$salaries->details['allowances']['Critical Allowance'] ?? '-',
                (float)$salaries->details['allowances']['Medical Allowance'] ?? '-',
                (float)$salaries->details['deductions']['Salary Tax'] ?? '-',
                (float)$salaries->details['allowances']['Add. Work Allowance'] ?? '-',
                (float)$salaries->details['allowances']['Cash Allowance'] ?? '-',
                (float)$salaries->details['deductions']['PF Contr'] ?? '-',
                (float)$salaries->details['deductions']['H/Tax'] ?? '-',
                (float)$salaries->details['deductions']['GSLI'] ?? '-',
                (float)$salaries->details['deductions']['SIFA'] ?? '-',
                (float)$salaries->details['deductions']['Loan BOB'] ?? '-',
                (float)$salaries->details['deductions']['Loan BNB'] ?? '-',
                (float)$salaries->details['deductions']['Loan TBank'] ?? '-',
                (float)$salaries->details['deductions']['Loan BDFC'] ?? '-',
                (float)$salaries->details['allowances']['Corporate Allowance'] ?? '-',
                (float)$salaries->details['deductions']['SSSS'] ?? '-',
                (float)$salaries->details['deductions']['Adv. Salary'] ?? '-',
                (float)$salaries->details['deductions']['Loan NPPF'] ?? '-',
                (float)$salaries->details['deductions']['Samsung Ded'] ?? '-',
                (float)$salaries->details['deductions']['Loan RICB'] ?? '-',
                (float)$salaries->details['deductions']['Loan DPNB'] ?? '-',
                (float)$salaries->details['deductions']['Loan SIFA'] ?? '-',
                (float)$salaries->details['allowances']['Difficulty Allowance'] ?? '-',
                (float)$salaries->details['deductions']['Adv. Staff'] ?? '-',
                (float)$salaries->details['gross_pay'] ?? '-',
                (float)$salaries->details['net_pay'] ?? '-',
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
