<?php

namespace App\Exports;

use App\Models\DsaClaimApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DSASettlementExport implements FromCollection, WithHeadings
{
    protected $request;

    // Constructor to receive request data
    public function __construct($request)
    {
        $this->request = $request;
    }



    public function collection()
    {
        $serialNo = 1;

        $statusClasses = [
            -1 => 'Rejected',
            0 => 'Cancelled',
            1 => 'Submitted',
            2 => 'Verified',
            3 => 'Approved',
        ];

        $dsaClaim = DsaClaimApplication::where('status', 3)->with([
            'employee.empJob.designation',
            'employee.empJob.department',
            'expense_approved_by',
            'dsaClaimDetails',
            'dsaClaimMappings.dsaDetails',
            'dsaClaimMappings.travelAuthorization',
            'dsaClaimMappings.advanceApplication',
            'dsaClaimMappings.dsaClaimApplication',
        ])->filter($this->request, false)->get();

        $data = $dsaClaim->flatMap(function ($claim) use (&$serialNo, $statusClasses) {
            $allDetails = collect();

            // Unmapped details
            foreach ($claim->dsaClaimDetails as $detail) {
                $allDetails->push([
                    'detail' => $detail,
                    'mapping' => null
                ]);
            }

            // Mapped details
            foreach ($claim->dsaClaimMappings as $mapping) {
                foreach ($mapping->dsaDetails as $detail) {
                    $allDetails->push([
                        'detail' => $detail,
                        'mapping' => $mapping
                    ]);
                }
            }

            return $allDetails->map(function ($entry) use (&$serialNo, $claim, $statusClasses) {
                $dsa = $entry['detail'];
                $mapping = $entry['mapping'];

                return [
                    $serialNo++,
                    getDisplayDateFormat($claim->created_at),
                    $claim->employee->emp_name,
                    $claim->employee->username,
                    $claim->employee->empJob->designation->name,
                    $claim->employee->empJob->department->name,
                    $claim->employee->empJob->office->region->name,
                    $claim->employee->empJob->office->name,
                    $claim->transaction_no,
                    $dsa->from_location,
                    $dsa->to_location,
                    getDisplayDateFormat($dsa->from_date),
                    getDisplayDateFormat($dsa->to_date),
                    $dsa->total_days,
                    $dsa->daily_allowance,
                    $dsa->travel_allowance,
                    $dsa->total_amount,
                    $mapping->travelAuthorization->transaction_no ?? $claim->travel->transaction_no, // Mapped or '-'
                    $mapping->advanceApplication->transaction_no ?? $claim->dsaadvance->transaction_no ?? '-',       // Mapped or '-'
                    formatAmount(optional(optional($mapping)->advanceApplication)->amount, false) ?? formatAmount(optional($claim->dsaadvance)->amount, false) ?? config('global.null_value'),
                    // formatAmount($mapping->advanceApplication->amount, false) ?? (formatAmount($claim->dsaadvance->amount, false) ?? config('global.null_value')),          // Mapped or '-'
                    formatAmount($claim->net_payable_amount, false),
                    $statusClasses[$claim->status] ?? 'Unknown Status',
                    $claim->expense_approved_by->emp_name ?? '-',
                    getDisplayDateFormat($claim->updated_at),
                ];
            });
        });

        return collect($data);
    }


    // Define column headings
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
            'DSA Claim No',
            'From Location',
            'To Location',
            'From Date',
            'To Date',
            'Total Day (s)',
            'Daily Allowance (Nu.)',
            'Travel Allowance (Nu.)',
            'Total Amount (Nu.)',
            'Travel Authorization No',
            'Advance No',
            'Advance Amount (Nu.)',
            'Net Payable Amount (Nu.)',
            'Status',
            'Approved By',
            'Approved On',
        ];
    }
}
