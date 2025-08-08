<?php

namespace App\Exports;

use App\Models\AssetTransferApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetTransferExport implements FromCollection, WithHeadings
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

        $AssetTransfers = AssetTransferApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($this->request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return $AssetTransfers->flatMap(function ($transfer) use (&$serialNo) {
            return $transfer->details->map(function ($detail) use (&$serialNo, $transfer) {
                return [
                    $serialNo++,
                    $transfer->type_id === 1 ? 'Employee-Employee' : 'Site-Site',
                    $transfer->employee->emp_id_name,
                    $transfer->employee->empJob->department->name ?? config('global.null_value') ,
                    $transfer->transaction_no,
                    $transfer->transaction_date,
                    $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $detail->receivedSerial?->asset_serial_no,
                    \Illuminate\Support\Str::limit($detail->receivedSerial?->asset_description, 50, '...'),
                    $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->uom ?? '-' ,
                    $detail->receivedSerial?->quantity ?? 1,
                    $detail->receivedSerial?->amount,
                    $transfer->fromEmployee->name ?? config('global.null_value') ,
                    $transfer->toEmployee->name ?? config('global.null_value'),
                    $transfer->fromSite->name ?? config('global.null_value') ,
                    $transfer->toSite->name ?? config('global.null_value') ,
                    \Carbon\Carbon::parse($detail->receivedSerial->commissionDetail->date_placed_in_service)->format('d-M-Y') ?? config('global.null_value'),
                    $transfer->reason_of_transfer ?? '-' ,
                    $transfer->received_acknowledged ? 'Acknowledged' : 'Not Acknowledged',
                    config("global.application_status.{$transfer->status}", 'Unknown'),
                    $transfer->histories->last()->approvedBy->emp_id_name ?? '-'  ,
                ];
            });
        });
    }


    public function headings(): array
    {
        return [
            'SL no',
            'Transfer Type',
            'Applicant',
            'Department',
            'Transfer No',
            'Application Date',
            'Asset No',
            'Item Description',
            'UOM',
            'QTY',
            'Amount (Nu.)',
            'From Employee',
            'To Employee',
            'From Site',
            'To Site',
            'Capitalization Date',
            'Reason of Transfer',
            'Transfer Acknowledgement',
            'Status',
            'Approved By',
        ];
    }
}
