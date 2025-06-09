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
        return TransferClaimApplication::with(['audit_logs' => function($query){
            $query->where('status', 3); 
        }])->whereStatus(3)->filter($this->request, false)->get()->map(function ($transfer) use (&$serialNo, $statusClasses) {
            return [
                $serialNo++,
                getDisplayDateFormat($transfer->created_at),
                $transfer->employee->emp_name,
                $transfer->employee->username,
                $transfer->employee->empJob->designation->name,
                $transfer->employee->empJob->department->name,
                $transfer->employee->empJob->office->region->name,
                $transfer->employee->empJob->office->name,
                $transfer->type->name,
                optional(json_decode(optional($transfer->audit_logs->first())->sap_response, true))['data']['JdtNum'] ?? config('global.null_value'),
                $transfer->current_location,
                $transfer->distance_travelled,
                $transfer->new_location,
                formatAmount($transfer->amount, false),
                $statusClasses[$transfer->status],
                $transfer->transfer_approved_by->emp_name ?? config('global.null_value'),
                getDisplayDateFormat($transfer->updated_at),

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
            'Ofiice Location',
            'Transfer Claim Type',
            'SAP Trans No',
            'From Location',
            'Distance (KM)',
            'Current Location',
            'Expense Amount (Nu.)',
            'Status',
            'Approved By',
            'Approved On'

        ];
    }
}
