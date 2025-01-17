<?php

namespace App\Exports;

use App\Models\TransferClaimApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransferClaimExport implements FromCollection,WithHeadings
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

        $statusClasses = [
            -1 => 'Rejected',
            0 => 'Cancelled',
            1 => 'Submitted',
            2 => 'Verified',
            3 => 'Approved',
        ];

        // Access the request data to apply filters
        return TransferClaimApplication::filter($this->request, false)->get()->map(function ($transfer) use (&$serialNo, $statusClasses) {
            return [
                $serialNo++,
                $transfer->employee->name,
                $transfer->employee->empJob->designation->name,
                $transfer->employee->empJob->department->name,
                $transfer->type->name,
                $transfer->current_location,
                $transfer->distance_travelled,
                $transfer->new_location,
                $transfer->expense_amount,
                $statusClasses[$transfer->status],
                $transfer->transfer_approved_by->name,

            ];
        });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Designation',
            'Department',
            'Transfer Claim Type',
            'From Location',
            'Distance',
            'Current Location',
            'Expense Amount',
            'Status',
            'Approved By',
            'Date'

        ];
    }
}
