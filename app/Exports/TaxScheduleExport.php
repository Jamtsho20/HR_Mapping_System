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
                array_sum($taxSchedules->details['allowances'] ?? []), // Sum all allowances
                $taxSchedules->details['gross_pay'] ?? 0,
                $taxSchedules->details['deductions']['PF Contr'] ?? 0,
                $taxSchedules->details['deductions']['GSLI'] ?? 0,
                ($taxSchedules->details['gross_pay'] ?? 0) - (($taxSchedules->details['deductions']['PF Contr'] ?? 0) + ($taxSchedules->details['deductions']['GSLI'] ?? 0)),
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
            'Allowance',
            'Gross Salary',
            'Provident Fund',
            'GIS',
            'Net Salary',
            'Health Tax',
            'Salary Tax',
            'Total Tax',
            'Date',
        ];
    }
}
