<?php

namespace App\Exports;

use App\Models\EmployeeSalarySaving;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SSSExport implements FromCollection, WithHeadings
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

        // Access the request data to apply filters
        return EmployeeSalarySaving::join('final_pay_slips', 'final_pay_slips.mas_employee_id', '=', 'employee_salary_savings.employee_id')->filter($this->request)->get()->map(function ($sss) use (&$serialNo) {
            return [
                $serialNo++,
                $sss->employee->username,
                $sss->employee->name,
                $sss->policy_number ?? '-',
                $sss->amount,
                $sss->for_month,

            ];
        });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
            'Employee Name',
            'Policy Number',
            'SSS Amount',


        ];
    }
}
