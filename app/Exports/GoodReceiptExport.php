<?php

namespace App\Exports;

use App\Models\ReceivedSerial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoodReceiptExport implements FromCollection, WithHeadings
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

        $receivedSerials = ReceivedSerial::filter($this->request, false)
            ->where('is_received',1)
            ->orderBy('created_at', 'desc')
            ->get();
        return $receivedSerials->map(function ($serials) use (&$serialNo) {
            return [
                $serialNo++,
                $serials->requisitionDetail->requisition->employee->emp_id_name ?? config('global.null_value'),
                $serials->requisitionDetail->requisition->employee->empJob->department->name ?? config('global.null_value'),
                $serials?->requisitionDetail?->requisition?->transaction_no ?? config('global.null_value'),
                $serials?->requisitionDetail?->requisition?->good_issue_doc_no ?? config('global.null_value'),
                $serials?->requisitionDetail?->grnItemDetail?->store?->name ?? config('global.null_value'),
                $serials?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $serials?->asset_serial_no  ?? config('global.null_value'),
                \Illuminate\Support\Str::limit($serials?->asset_description, 75, '...'),
                $serials?->requisitionDetail?->grnItemDetail?->item?->uom ?? config('global.null_value'),
                $serials?->quantity ?? 1,
                $serials?->amount ?? config('global.null_value'),
                $serials->requisitionDetail?->dzongkhag->dzongkhag ?? config('global.null_value'),
                $serials->requisitionDetail?->site->name ?? config('global.null_value'),
                // $serials->commissionDetail?->date_placed_in_service ?? config('global.null_value'),
                // $serials->is_received ? 'Received' : 'Not Received' ?? config('global.null_value'),
                $serials->remark ?? config('global.null_value'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Goods Received By',
            'Department',
            'Req No',
            'GIN',
            'Goods Issued From (Store)',
            'Asset No',
            'Item Description',
            'UOM',
            'QTY',
            'Amount',
            'Dzongkhag',
            'Site',
            'Remark'
        ];

    }
    }
