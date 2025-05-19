<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
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

        return FinalPaySlip::filter($this->request)->get()
            ->filter(function ($sifaContributions) {
                return ($sifaContributions->details['deductions']['SIFA'] ?? 0) > 0;
            })
            ->map(function ($sifaContributions) use (&$serialNo) {
                return [
                    $serialNo++,
                    $sifaContributions->employee->username ?? '-',
                    $sifaContributions->employee->name ?? '-',
                    $sifaContributions->employee->empJob->designation->name ?? '-',
                    $sifaContributions->employee->empJob->empType->name ?? '-',
                    $sifaContributions->details['deductions']['SIFA'] ?? 0,
                    $sifaContributions->for_month ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
            'Employee Name',
            'Job Title',
            'Employment Type',
            'SIFA',
            'Date',

        ];
    }
}
