<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashExport implements FromCollection, WithHeadings
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
            $query->where('salary_disbursement_mode', 1);
        })->filter($this->request) // Apply the filter scope on the main query
            ->get()
            ->map(function ($cash) use (&$serialNo) {
                return [
                    $serialNo++,
                    $cash->employee->name,
                    $cash->employee->empJob->designation->name,
                    $cash->employee->empJob->office->name,
                    $cash->details['net_pay'],
                    $cash->for_month,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Job Title',
            'Work Loaction',
            'Net Payment',
            'Date',

        ];
    }
}
