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
                    $claim->employee->username,
                    $claim->employee->name,
                    $claim->employee->empJob->designation->name,
                    $claim->employee->empJob->department->name,
                    $claim->transaction_no,
                    $dsa->from_location,
                    $dsa->to_location,
                    $dsa->from_date,
                    $dsa->to_date,
                    $dsa->total_days,
                    $dsa->daily_allowance,
                    $dsa->travel_allowance,
                    $dsa->total_amount,
                    $mapping->travelAuthorization->transaction_no ?? $claim->travel->transaction_no, // Mapped or '-'
                    $mapping->advanceApplication->transaction_no ?? $claim->dsaadvance->transaction_no ?? '-',          // Mapped or '-'
                    $mapping->advanceApplication->amount ?? $claim->dsaadvance->amount ?? '-',          // Mapped or '-'
                    $claim->net_payable_amount,
                    $statusClasses[$claim->status] ?? 'Unknown Status',
                    $claim->expense_approved_by->name ?? '-',
                    $claim->updated_at->format('m-d-Y'),
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
            'Employee Id',
            'Employee Name',
            'Designation',
            'Department',
            'DSA Claim No',
            'From Location',
            'To Location',
            'From Date',
            'To Date',
            'Total Days',
            'Daily Allowance',
            'Travel Allowance',
            'Total Amount',
            'Travel Authorization No',
            'Advance No',
            'Advance Amount',
            'Net Payable Amount',
            'Status',
            'Approved By',
            'Approved Date     ',
        ];
    }
}
