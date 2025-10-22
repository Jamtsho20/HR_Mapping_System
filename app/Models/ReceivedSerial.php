<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedSerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_detail_id',
        'asset_serial_no',
        'batch_no',
        'asset_description',
        'is_commissioned',
        'is_transfered_to',
        'is_transfered',
        'amount',
        'remark',
        'asset_no',
        'is_received',
    ];

    public function requisitionDetail()
    {
        return $this->belongsTo(RequisitionDetail::class, 'requisition_detail_id');
    }

    public function commissionDetail()
    {
        return $this->hasOne(AssetCommissionDetail::class, 'received_serial_id');
    }

    public function transferDetail()
    {
        return $this->hasOne(AssetTransferDetail::class, 'received_serial_id');
    }

    public function returnDetailsDetail()
    {
        return $this->hasOne(AssetReturnDetail::class, 'received_serial_id');
    }


     public function scopeFilter($query, $request, $onesOwnRecord = true)
    {

       if ($request->from_date && $request->to_date) {
            $toDate = $request->to_date . ' 23:59:59';
            $query->where('created_at', '>=', $request->from_date)
                ->where('created_at', '<=', $toDate);
        } elseif ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }

        $query->whereHas('requisitionDetail', function ($q) use ($request) {
            if ($request->received_from_date && $request->received_to_date) {
                $receivedToDate = $request->received_to_date . ' 23:59:59';
                $q->whereBetween('received_at', [$request->received_from_date, $receivedToDate]);
            } elseif ($request->received_from_date) {
                $q->where('received_at', '>=', $request->received_from_date);
            }
        });


        if($request->is_received !== null){
        $query->where('is_received', $request->is_received);
        }

        if($request->gin){
            $query->whereHas('requisitionDetail.requisition', function ($q) use ($request) {
                $q->whereRaw("SUBSTRING_INDEX(good_issue_doc_no, '-', -1) = ?", [$request->gin]);
            });
        }

        if($request->req_no){
            $reqNo = urldecode($request->req_no);
            $query->whereHas('requisitionDetail.requisition', function ($q) use ($reqNo) {
                $q->where('transaction_no', $reqNo);
            });
        }

        if ($request->serial_no) {

            $lastPart = last(explode('-', $request->serial_no));

            $query->where('asset_serial_no', $lastPart);
        }

        if($request->grn){
            $query->whereHas('requisitionDetail.grnItem', function ($q) use ($request) {
                $q->whereRaw("SUBSTRING_INDEX(grn_no, '-', -1) = ?", [$request->grn]);
            });
        }

        if ($request->get('year')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('year'));

            // Step 2: Convert each date to Y-m format using Carbon
            $startDate = Carbon::createFromFormat('Y-m', trim($dates[0]));

            // Extract year and month
            $year = $startDate->year;
            $month = $startDate->month;

            // Filter by year and month
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }
    }
}
