<?php

namespace App\Exports;

use App\Models\RequisitionApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RequisitionExport implements FromCollection, WithHeadings
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

        $requisitions = RequisitionApplication::with([
            'employee',
            'type',
            'approvedBy',
            'details.grnItem',
            'details.grnItemDetail.item',
            'details.grnItemDetail.store',
            'details.dzongkhag',
            'details.site',
            'audit_logs'
        ])
            ->whereIn('status', [-1, 3])
            ->filter($this->request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        return $requisitions->flatMap(function ($req) use (&$serialNo) {
            return $req->details->map(function ($detail) use (&$serialNo, $req) {
                return [
                    $serialNo++,
                    $req->employee->emp_id_name ?? '-',
                    $req->employee->empJob->department->name ?? config('global_null_value'),
                    $req->type->name ?? '-',
                    $req->transaction_no,
                    $req->transaction_date,
                    $req->doc_no ?? config('global.null_value'),
                    $detail->grnItem->grn_no ?? config('global.null_value'),
                    $detail->grnItemDetail->item->item_description ?? '',
                    $detail->grnItemDetail->item->uom ?? config('global.null_value'),
                    $detail->grnItemDetail->store->name ?? '-',
                    $detail->grnItemDetail->quantity ?? 0,
                    $detail->requested_quantity ?? 0,
                    $detail->received_quantity ?? 0,
                    $detail->dzongkhag->dzongkhag ?? config('global.null_value'),
                    $detail->site->name ?? config('global.null_value'),
                    $detail->remark ?? config('global.null_value'),
                    config("global.application_status.{$req->status}", 'Unknown'),
                    $req->histories->last()->approvedBy->emp_id_name ?? '-',
                    $req->is_received ? 'Received' : 'Not Received',
                ];
            });
        });
    }


    public function headings(): array
    {
        return [
            'Sl No',
            'Appplicant',
            'Department',
            'REQ Type',
            'REQ No.',
            'Application Date',
            'SAP Doc No',
            'GRN',
            'Item Description',
            'UOM',
            'Store',
            'Stock Status',
            'Quantity Requested',
            'Quantity Received',
            'Dzongkhag',
            'Site',
            'Remarks',
            'Status',
            'Approved By',
            'Is Received',

        ];
    }
}
