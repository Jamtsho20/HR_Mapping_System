<?php

namespace App\Exports;

use App\Models\finalPaySlip;
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

        // Access the request data to apply filters
        return FinalPaySlip::filter($this->request)->get()->map(function ($gis) use (&$serialNo) {
            return [
                $serialNo++,
                $gis->employee->name,
                '-',
                $gis->employee->cid_no,
                $gis->employee->dob,
                $gis->employee->empJob->basic_pay,
                $gis->details['deductions']['GSLI'] ?? '0',
                $gis->for_month,
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
