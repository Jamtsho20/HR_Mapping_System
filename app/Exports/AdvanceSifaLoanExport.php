<?php

namespace App\Exports;

use App\Models\SifaLoanRepayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdvanceSifaLoanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
        protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $serialNo = 1;

        return SifaLoanRepayment::filter($this->request, false)->get()->map(function ($AdvanceReports) use (&$serialNo) {
            return [
                $serialNo++,              
                $AdvanceReports->advanceApplication->employee->emp_name,
                $AdvanceReports->advanceApplication->advanceType->name,
                $AdvanceReports->advanceApplication->amount,
                $AdvanceReports->advanceApplication->transaction_date,
                $AdvanceReports->advanceApplication->deduction_from_period,
                $AdvanceReports->advanceApplication->no_of_emi,
                $AdvanceReports->advanceApplication->monthly_emi_amount,
                $AdvanceReports->advanceApplication->updated_at,
                $AdvanceReports->repayment_number,
                $AdvanceReports->opening_balance,
                $AdvanceReports->interest_charged,
                $AdvanceReports->principal_repaid,
                $AdvanceReports->closing_balance,

                
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Advance Type',
            'Principal Amount',
            'Date of Claim',
            'EMI Start Date',
            'No of EMI',
            'EMI Amount',
            'Date of Sanction',
            'Repayment Number',
            'Opening Balance',
            'Interest Charged',
            'Principal Repaid',
            'Closing Balance'

            
        ];
    }
}
