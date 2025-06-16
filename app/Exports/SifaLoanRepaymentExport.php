<?php

namespace App\Exports;

use App\Models\AdvanceApplication;
use App\Models\SifaLoanRepayment;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifaLoanRepaymentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return SifaLoanRepayment::all();
    // }
    protected $request;
    
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $serialNo = 1;

        return AdvanceApplication::whereStatus(4)->filter($this->request, false)->get()->map(function ($AdvancesifaReports) use (&$serialNo) {
            return [
                $serialNo++,
                $AdvancesifaReports->employee->username,
                $AdvancesifaReports->employee->name,
                $AdvancesifaReports->employee->empJob->designation->name,
                $AdvancesifaReports->employee->empJob->department->name,
                $AdvancesifaReports->employee->empJob->office->name,
                $AdvancesifaReports->advanceType->name,
                \Carbon\Carbon::parse($AdvancesifaReports->transaction_date)->format('d-F-Y'),
                $AdvancesifaReports->amount,
                \Carbon\Carbon::parse($AdvancesifaReports->deduction_from_period)->format('d-F-Y'),
                $AdvancesifaReports->no_of_emi,
                $AdvancesifaReports->monthly_emi_amount,
                \Carbon\Carbon::parse($AdvancesifaReports->deduction_from_period)->addMonths($AdvancesifaReports->no_of_emi - 1)->format('d-F-Y'), // Adding months
                $AdvancesifaReports->advance_approved_by->name,
                \Carbon\Carbon::parse($AdvancesifaReports->updated_at)->format('d-F-Y')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SL No',
            'CODE',
            'NAME',
            'DESIGNATION',
            'DEPARTMENT',
            'LOCATION',
            'ADVANCE LOAN TYPE',
            'DATE OF CLAIM',
            'AMOUNT',
            'EMI START DATE',
            'NO OF EMI',
            'EMI Amount',
            'EMI END DATE',
            'APPROVED BY',
            'APPROVAL DATE'
        ];
    }
}
