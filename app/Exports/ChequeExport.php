<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
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
        })->filter($this->request) // Apply the filter scope on the main query
            ->get()
            ->map(function ($cheque) use (&$serialNo) {
                return [
                    $serialNo++,
                    $cheque->employee->name,
                    $cheque->employee->empJob->account_number,
                    $cheque->employee->empJob->bank,
                    $cheque->details['net_pay'],
                    $cheque->for_month,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Bank Account Number',
            'Bank Loaction',
            'Net Payment',
            'Date',

        ];
    }
}
