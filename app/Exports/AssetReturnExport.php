<?php

namespace App\Exports;

use App\Models\AssetReturnApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetReturnExport implements FromCollection, WithHeadings
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

        $AssetReturn = AssetReturnApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($this->request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return $AssetReturn->flatMap(function ($return) use (&$serialNo) {
            return $return->details->map(function ($detail) use (&$serialNo, $return) {
                return [
                    $serialNo++,
                    $return->employee->emp_id_name,
                    $return->employee->empJob->department->name ?? config('global.null_value') ,
                    $return->transaction_no,
                    $return->transaction_date,
                    $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $detail->receivedSerial?->asset_serial_no,
                    \Illuminate\Support\Str::limit($detail->receivedSerial?->asset_description, 50, '...'),
                    $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->uom ?? '-' ,
                    $detail->receivedSerial?->quantity ?? 1,
                    $detail->receivedSerial?->amount,
                    $return->employee->name ?? config('global.null_value') ,
                    $return->store->name ?? config('global.null_value') ,
                    \Carbon\Carbon::parse($detail->receivedSerial->commissionDetail->date_placed_in_service)->format('d-M-Y') ?? config('global.null_value'),
                    $detail->remark ?? '-' ,
                    $return->received_acknowledged ? 'Acknowledged' : 'Not Acknowledged',
                    config("global.application_status.{$return->status}", 'Unknown'),
                    $return->histories->last()->approvedBy->emp_id_name ?? '-'  ,
                ];
            });
        });
    }


    public function headings(): array
    {
        return [
            'SL no',
            'Applicant',
            'Department',
            'Return No',
            'Application Date',
            'Asset No',
            'Item Description',
            'UOM',
            'QTY',
            'Amount (Nu.)',
            'From Employee',
            'To Employee',
            'To Store',
            'Capitalization Date',
            'Reason of Transfer',
            'Return Acknowledgement',
            'Status',
            'Approved By',
        ];
    }
}




