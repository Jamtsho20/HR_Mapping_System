<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaxScheduleExport implements FromCollection, WithHeadings
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
        return FinalPaySlip::filter($this->request)->get()->map(function ($taxSchedules) use (&$serialNo) {
            $hTax = $taxSchedules->details['deductions']['H/Tax'] ?? 0;
            $tds =  $taxSchedules->details['deductions']['Salary Tax'] ?? '0';


            return [
                $serialNo++,
                $taxSchedules->employee->name,
                $taxSchedules->employee->empJob->tpn_number,
                $taxSchedules->employee->cid_no,
                $taxSchedules->details['basic_pay'] ?? 0,
                $taxSchedules->details['allowances']['Critical ALL'] ?? '0',
                $taxSchedules->details['allowances']['House ALL'] ?? '0',
                $taxSchedules->details['allowances']['Medical ALL'] ?? '0',
                $taxSchedules->details['allowances']['Corporate ALL'] ?? '0',
                $taxSchedules->details['allowances']['Cash ALL'] ?? '0',
                $taxSchedules->details['deductions']['H/Tax'] ?? 0,
                $taxSchedules->details['deductions']['Salary Tax'] ?? 0,
                $hTax +  $tds,
                $taxSchedules->for_month,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'TPN',
            'CID',
            'Basic Pay',
            'Critical ALL',
            'House ALL',
            'Medical ALL',
            'Coporate ALL',
            'Cash ALL',
            'H/Tax',
            'Salary Tax',
            'Total Tax',
            'Date',
        ];
    }
}
