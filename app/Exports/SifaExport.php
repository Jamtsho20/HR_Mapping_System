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
                    $item->employee->name ?? '-',
                    $item->employee->empJob->designation->name ?? '-',
                    $item->employee->empJob->empType->name ?? '-',
                    $details['deductions']['SIFA'] ?? 0,
                    $item->for_month ?? '-',
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
                    $item->employee->name ?? '-',
                    $item->employee->empJob->designation->name ?? '-',
                    $item->employee->empJob->empType->name ?? '-',
                    floatval($item->sifa_contr),
                    $item->for_month ?? '-',
                ];
            });

        // 3. Merge both collections
        return $oldData->isNotEmpty() && $newData->isNotEmpty()
            ? $oldData->merge($newData)->values()
            : ($oldData->isNotEmpty() ? $oldData->values() : ($newData->isNotEmpty() ? $newData->values() : collect()));
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
