<?php

namespace App\Exports;

use App\Models\AdvanceApplication;
use App\Models\SifaLoanRepayment;
use App\Models\User;
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

        // Step 1: Get all filtered, approved AdvanceApplications
        $advanceApplications = AdvanceApplication::whereStatus(4)
            ->filter($this->request, false)
            ->with(['employee.empJob', 'advanceType']) // eager-load for export
            ->get();

        // Step 2: Get all relevant SifaLoanRepayments
        $repayments = SifaLoanRepayment::whereIn('advance_application_id', $advanceApplications->pluck('id'))
            ->with(['advanceApplication.employee.empJob', 'advanceApplication.advanceType', 'advanceApplication.emiDeduction'])
            ->get();


        // Step 3: Map results for export
        return $repayments->map(function ($repayment) use (&$serialNo) {
            $advance = $repayment->advanceApplication;
            $paidOffBy = '';

            // Check if paid_off is 1 to display the paid off date
            $paidOffDate = '';
            if (
                $repayment->advanceApplication->emiDeduction &&
                $repayment->advanceApplication->emiDeduction->is_paid_off == 1
            ) {
                $paidOffDate = getDisplayDateFormat($repayment->advanceApplication->emiDeduction->updated_at);
                // $paidOffBy = $repayment->advanceApplication->emiDeduction->paid_off_by;
                $user = User::find($repayment->advanceApplication->emiDeduction->paid_off_by);
                $paidOffBy = $user->name ?? $user->username ?? '-';
            }

            return [
                $serialNo++,
                $advance->employee->emp_name ?? '-',
                $advance->employee->username ?? '-',
                $advance->advanceType->name ?? '-',
                $advance->amount,
                $advance->net_payable,
                getDisplayDateFormat($advance->transaction_date),
                getDisplayDateFormat($advance->deduction_from_period),
                $advance->no_of_emi,
                $advance->monthly_emi_amount,
                getDisplayDateFormat($advance->updated_at),
                $repayment->repayment_number,
                $repayment->opening_balance,
                $repayment->interest_charged,
                $repayment->principal_repaid,
                $repayment->closing_balance,
                $paidOffDate,
                $paidOffBy,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Employee ID',
            'Advance Type',
            'Principal Amount',
            'Net Payable Amount',
            'Date of Claim',
            'EMI Start Date',
            'No of EMI',
            'EMI Amount',
            'Date of Sanction',
            'Repayment Number',
            'Opening Balance',
            'Interest Charged',
            'Principal Repaid',
            'Closing Balance',
            'Paid Off Date',
            'Paid Off By',
        ];
    }
}
