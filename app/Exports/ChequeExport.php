<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChequeExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
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
        return FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
        })
            ->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id')
            ->join('mas_employee_jobs', 'mas_employees.id', '=', 'mas_employee_jobs.mas_employee_id')
            ->leftJoin('mas_pay_group_details', function ($join) {
                $join->on('mas_employee_jobs.mas_grade_id', '=', 'mas_pay_group_details.mas_grade_id')
                    ->where('mas_pay_group_details.mas_pay_group_id', 4);
            })
            ->where('mas_employees.is_active', 1)
            ->select(
                'final_pay_slips.*',
                'mas_employees.name',
                'mas_employee_jobs.account_number',
                'mas_employee_jobs.bank',
                DB::raw('(JSON_UNQUOTE(JSON_EXTRACT(final_pay_slips.details, "$.net_pay")) - COALESCE(mas_pay_group_details.amount, 0)) as net_pay_after_eteeru')
            )->filter($this->request) // Apply the filter scope on the main query
            ->get()
            ->map(function ($cheque) use (&$serialNo) {
                return [
                    $serialNo++,
                    $cheque->employee->name,
                    $cheque->employee->username,
                    $cheque->employee->empJob->account_number,
                    $cheque->employee->empJob->bank_code,
                    $cheque->employee->empJob->bank,
                    $cheque->net_pay_after_eteeru,
                    $cheque->for_month,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Employee ID',
            'Bank Account Number',
            'Branch code',
            'Bank Loaction',
            'Net Payment',
            'Date',

        ];
    }
}
