<?php

namespace App\Exports;

use App\Models\ReceivedSerial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Iluminate\Http\Request;

class CwipReportExport implements FromCollection, WithHeadings
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

        $receivedSerials = ReceivedSerial::with([
            'requisitionDetail.grnItemDetail.item',
        ])
            ->where('is_commissioned', 0)
            ->filter($this->request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        return $receivedSerials->map(function ($serial) use (&$serialNo) {
                return [
                    $serialNo++,
                    $serial->requisitionDetail?->grnItemDetail->item->item_group_id ?? config('global.null_value'),
                    config('global.asset_class')[$serial->requisitionDetail?->grnItemDetail->item->item_group_id]
                        ?? $serial->requisitionDetail?->grnItemDetail->item->item_group_id
                        ?? config('global.null_value'),
                    $serial->requisitionDetail?->requisition->transaction_no ?? config('global.null_value'),
                    $serial->requisitionDetail?->grnItemDetail->grn->grn_no ?? config('global.null_value'),
                    $serial->requisitionDetail?->grnItemDetail->item->item_no .'-'.$serial->asset_serial_no ?? config('global.null_value'),
                    $serial->asset_description ?? config('global.null_value'),
                    $serial->requisitionDetail?->grnItemDetail->item->uom ?? config('global.null_value'),
                    $serial->quantity ?? 1,
                    $serial->requisitionDetail?->received_at ?? config('global.null_value'),
                    $serial->amount ?? config('global.null_value'),
                    $serial->requisitionDetail?->grnItemDetail->store->code ?? config('global.null_value'),
                    $serial->requisitionDetail?->requisition->employee->username ?? config('global.null_value'),
                    $serial->requisitionDetail?->requisition->employee->name ?? config('global.null_value'),
                    $serial->requisitionDetail?->dzongkhag->dzongkhag ?? config('global.null_value'),
                    $serial->requisitionDetail?->site->code ?? config('global.null_value'),
                    $serial->requisitionDetail?->site->name ?? config('global.null_value')
                ];
        });
    }


    public function headings(): array
    {
        return [
            'Sl No',
            'Asset Class Code',
            'Asset Class Name',
            'Requisition No.',
            'GRN',
            'Serial No',
            'Item Description',
            'UOM',
            'QTY',
            'Goods Received Date',
            'Cost',
            'Issued From',
            'Employee Code',
            'Employee Name',
            'Dzongkhag',
            'Project Code',
            'Project Name'

        ];
    }
}
