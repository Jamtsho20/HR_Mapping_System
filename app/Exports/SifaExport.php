<?php

namespace App\Exports;

use App\Models\finalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SifaExport implements FromCollection, WithHeadings
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
        return FinalPaySlip::filter($this->request)->get()->map(function ($sifaContributions) use (&$serialNo) {
            return [
                $serialNo++,
                $sifaContributions->employee->name,
                $sifaContributions->employee->empJob->designation->name,
                $sifaContributions->employee->empJob->empType->name,
                $sifaContributions->details['deductions']['SIFA'] ?? '0',
                $sifaContributions->for_month,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Job Title',
            'Employee Status',
            'SIFA',
            'Date',

        ];
    }
}
