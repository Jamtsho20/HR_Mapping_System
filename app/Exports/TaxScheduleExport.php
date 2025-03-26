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
                $taxSchedules->details['basic_pay'] ?? '-',
                array_sum($taxSchedules->details['allowances'] ?? []), // Sum all allowances
                $taxSchedules->details['gross_pay'] ?? '-',
                $taxSchedules->details['deductions']['GSLI'] ?? '-',
                $taxSchedules->details['net_pay'] ?? '-',
                $taxSchedules->details['deductions']['H/Tax'] ?? '-',
                $taxSchedules->details['deductions']['Salary Tax'] ?? '-',
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
            'Allowance',
            'Gross Salary',
            'GIS',
            'Net Salary',
            'Health Tax',
            'Salary Tax',
            'Total Tax',
            'Date',
        ];
    }
}
