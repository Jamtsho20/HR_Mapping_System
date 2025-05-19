<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GISExport implements FromCollection, WithHeadings
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

        return FinalPaySlip::filter($this->request)
            ->get()
            ->filter(function ($gis) {
                return ($gis->details['deductions']['GSLI'] ?? 0) > 0;
            })
            ->map(function ($gis) use (&$serialNo) {
                return [
                    $serialNo++,
                    $gis->employee->name ?? '-',
                    $gis->employee->empJob->gis_policy_number ?? '-',
                    $gis->employee->cid_no ?? '-',
                    $gis->employee->dob ?? '-',
                    $gis->employee->empJob->basic_pay ?? 0,
                    $gis->details['deductions']['GSLI'] ?? 0,
                    $gis->for_month ?? '-',
                ];
            });
    }


    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Policy Number',
            'CID',
            'DOB',
            'Basic',
            'GIS Amount',
            'Date',
        ];
    }
}
