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
        return EmployeeSalarySaving::join('final_pay_slips', 'final_pay_slips.mas_employee_id', '=', 'employee_salary_savings.employee_id')->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id')
            ->where('mas_employees.is_active', 1)->when($this->request->employee, function ($query, $name) {
                return $query->where('employee_salary_savings.employee_id', '=', $name);
            })

            // Filter `final_pay_slips` table (e.g., for specific month)
            ->when($this->request->year, function ($query, $month) {
                return $query->where('final_pay_slips.for_month', 'like', "{$month}%");
            })->get()->map(function ($sss) use (&$serialNo) {
                return [
                    $serialNo++,
                    $sss->employee->username,
                    $sss->employee->name,
                    $sss->employee->cid_no,
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
            'CID',
            'Policy Number',
            'SSS Amount',


        ];
    }
}
