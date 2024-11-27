<?php

namespace App\Exports;

use App\Models\AdvanceApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdvanceLoanExport implements FromCollection, WithHeadings
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
        return AdvanceApplication::filter($this->request, false)->get()->map(function ($AdvanceReports) use (&$serialNo) {
            return [
                $serialNo++,              
               $AdvanceReports->employee->username,
               $AdvanceReports->employee->name,
               $AdvanceReports->employee->empJob->designation->name,
               $AdvanceReports->employee->empJob->department->name,
               $AdvanceReports->employee->empJob->office->name,
               $AdvanceReports->advanceType->name,
               $AdvanceReports->date,
               $AdvanceReports->amount,
               $AdvanceReports->from_date,
               $AdvanceReports->no_of_emi,
               $AdvanceReports->to_date,
               $AdvanceReports->advance_approved_by->name,
               $AdvanceReports->updated_at->format('d-m-Y'),
            ];
        });
    }
    public function headings(): array
    {
        return [

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
            'EMI END DATE',
            'APPROVED BY',
            'APPROVAL DATE'
        ];
    }
}
