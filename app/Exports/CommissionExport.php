<?php

namespace App\Exports;

use App\Models\AssetCommissionApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CommissionExport implements FromCollection, WithHeadings
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

        $commissions = AssetCommissionApplication::with([
            'employee',
            'approvedBy',
            'details.receivedSerial.requisitionDetail.grnItemDetail.item',
            'details.dzongkhag',
            'details.site',
            'audit_logs',
        ])
            ->whereIn('status', [-1, 3])
            ->filter($this->request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        return $commissions->flatMap(function ($comm) use (&$serialNo) {
            return $comm->details->map(function ($detail) use (&$serialNo, $comm) {
                return [
                    $serialNo++,
                    $comm->employee->emp_id_name ?? '-',
                    $comm->transaction_no,
                    $comm->transaction_date,
                    $detail->receivedSerial->asset_serial_no ?? '-',
                    \Illuminate\Support\Str::limit($detail->receivedSerial->asset_description ?? '', 25, '...'),
                    $detail->receivedSerial->requisitionDetail->grnItemDetail->item->uom ?? '-',
                    1, // Quantity is always 1
                    $detail->receivedSerial->amount ?? 0,
                    $detail->dzongkhag->dzongkhag ?? '-',
                    optional($detail->date_placed_in_service)->format('d-M-Y'),
                    $detail->site->name ?? '-',
                    $detail->remark ?? '-',
                    config("global.application_status.{$comm->status}", 'Unknown'),
                    $comm->approvedBy->emp_id_name ?? '-',
                ];
            });
        });
    }


    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Comm No.',
            'Comm Date',
            'Asset No.',
            'Item Description',
            'UOM',
            'Quantity',
            'Amount',
            'Date Placed In Service',
            'Site',
            'Remarks',
            'Status',
            'Approved By',

        ];
    }
}
