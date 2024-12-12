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

    // Fetch and structure data for export
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

        // Fetch DSA claims with necessary relationships
        return DsaClaimApplication::filter($this->request, false)
            ->with(['dsaClaimDetails'])
            ->get()
            ->flatMap(function ($claim) use (&$serialNo, $statusClasses) {
                return $claim->dsaClaimDetails->map(function ($dsa) use (&$serialNo, $claim, $statusClasses) {
                    return [
                        $serialNo++,
                        $claim->employee->name,
                        $claim->employee->empJob->designation->name,
                        $claim->employee->empJob->department->name,
                        $dsa->from_location,
                        $dsa->to_location,
                        $dsa->from_date,
                        $dsa->to_date,
                        $dsa->total_days,
                        $dsa->daily_allowance,
                        $dsa->travel_allowance,
                        $dsa->total_amount,
                        $claim->travel->travel_authorization_no ?? '-',
                        $claim->dsaadvance->amount ?? '-',
                        $claim->net_payable_amount,
                        $statusClasses[$claim->status] ?? 'Unknown Status',
                        $claim->expense_approved_by->name ?? '-',
                        $claim->updated_at->format('m-d-Y'),
                    ];
                });
            });
    }

    // Define column headings
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Designation',
            'Department',
            'From Location',
            'To Location',
            'From Date',
            'To Date',
            'Total Days',
            'Daily Allowance',
            'Travel Allowance',
            'Total Amount',
            'Travel Authorization No',
            'Advance Amount',
            'Net Payable Amount',
            'Status',
            'Approved By',
            'Approved Date     ',
        ];
    }
}
