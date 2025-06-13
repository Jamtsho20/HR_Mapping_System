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

        return AdvanceApplication::whereStatus(3)->filter($this->request, false)->get()->map(function ($AdvanceReports) use (&$serialNo) {
            return [
                $serialNo++,
                getDisplayDateFormat($AdvanceReports->created_at),
                $AdvanceReports->employee->emp_name,
                $AdvanceReports->employee->username,
                $AdvanceReports->employee->empJob->designation->name,
                $AdvanceReports->employee->empJob->department->name,
                $AdvanceReports->employee->empJob->office->region->name,
                $AdvanceReports->employee->empJob->office->name,
                $AdvanceReports->advanceType->name,
                getDisplayDateFormat($AdvanceReports->date),
                formatAmount($AdvanceReports->amount, false),
                getDisplayDateFormat($AdvanceReports->deduction_from_period),
                $AdvanceReports->no_of_emi,
                formatAmount($AdvanceReports->monthly_emi_amount, false),
                getDisplayDateFormat(\Carbon\Carbon::parse($AdvanceReports->deduction_from_period)->addMonths($AdvanceReports->no_of_emi - 1)), // Adding months
                $AdvanceReports->advance_approved_by->emp_name,
                getDisplayDateFormat($AdvanceReports->updated_at)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Applied On',
            'Employee Name',
            'Employee Id',
            'Designation',
            'Department',
            'Region',
            'Office Location',
            'Advance Loan Type',
            'Date of Claim',
            'Amount (Nu.)',
            'EMI Start Date',
            'No of EMI',
            'EMI Amount (Nu.)',
            'EMI End Date',
            'Approved By',
            'Approved On'
        ];
    }
}
