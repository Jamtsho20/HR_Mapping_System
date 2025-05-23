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
        'asset_description',
        'is_commissioned',
        'is_transfered_to',
        'is_transfered',
        'amount',
        'remark',
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
}
