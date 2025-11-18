<?php

namespace App\Exports;

use App\Models\FinalPaySlip;
use App\Models\SifaContrHistorical;
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

        // return FinalPaySlip::filter($this->request)->get()
        //     ->filter(function ($sifaContributions) {
        //         return ($sifaContributions->details['deductions']['SIFA'] ?? 0) > 0;
        //     })
        //     ->map(function ($sifaContributions) use (&$serialNo) {
        //         return [
        //             $serialNo++,
        //             $sifaContributions->employee->username ?? '-',
        //             $sifaContributions->employee->name ?? '-',
        //             $sifaContributions->employee->empJob->designation->name ?? '-',
        //             $sifaContributions->employee->empJob->empType->name ?? '-',
        //             $sifaContributions->details['deductions']['SIFA'] ?? 0,
        //             $sifaContributions->for_month ?? '-',
        //         ];
        //     });
        $newData = FinalPaySlip::filter($this->request)->get()
            ->filter(function ($item) {
                $details = is_array($item->details)
                    ? $item->details
                    : json_decode($item->details, true);
                return ($details['deductions']['SIFA'] ?? 0) > 0;
            })
            ->map(function ($item) use (&$serialNo) {
                $details = is_array($item->details)
                    ? $item->details
                    : json_decode($item->details, true);

                return [
                    $serialNo++,
                    $item->employee->username ?? '-',
                    $item->employee->emp_name ?? '-',
                    $item->employee->empJob->designation->name ?? '-',
                    getDisplayDateFormat($item->employee->date_of_appointment),
                    $item->employee->empJob->empType->name ?? '-',
                    $details['deductions']['SIFA'] ?? 0,
                    // $item->for_month ?? '-',
                    \Carbon\Carbon::parse($item->for_month)->format('F Y') ?? '-'
                ];
            });

        // 2. Fetch Historical data
        $oldData = SifaContrHistorical::filter($this->request)->get()
            ->filter(function ($item) {
                return floatval($item->sifa_contr) > 0;
            })
            ->map(function ($item) use (&$serialNo) {
                return [
                    $serialNo++,
                    $item->employee->username ?? '-',
                    $item->employee->emp_name ?? '-',
                    $item->employee->empJob->designation->name ?? '-',
                    getDisplayDateFormat($item->employee->date_of_appointment),
                    $item->employee->empJob->empType->name ?? '-',
                    // floatval($item->sifa_contr),
                    $item->sifa_contr,
                    \Carbon\Carbon::parse($item->for_month)->format('F Y') ?? '-'
                ];
            });

        // 3. Merge both collections
        return $oldData->isNotEmpty() && $newData->isNotEmpty()
            ? $oldData->merge($newData)->values()
            : ($oldData->isNotEmpty() ? $oldData->values() : ($newData->isNotEmpty() ? $newData->values() : collect()));

        //incase if report is required from 2025 onwards no need to merge as there will duplication for Jan month
        // return $newData->isNotEmpty() ? $newData->values() : collect(); 
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
            'Employee Name',
            'Designation',
            'DOA',
            'Employment Type',
            'Amount',
            'For Month',

        ];
    }
}
