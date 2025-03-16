<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id', 'requested_quantity', 'received_quantity', 'commissioned_quantity', 'status', 'grn_item_id', 'grn_item_detail_id',  'site_id', 'dzongkhag_id', 'office_id', 'remark'
    ];

    public function requisition()
    {
        return $this->belongsTo(RequisitionApplication::class, 'requisition_id');
    }

    public function serials()
    {
        return $this->hasMany(ReceivedSerial::class, 'requisition_detail_id');
    }

    public function grnItem()
    {
        return $this->belongsTo(MasGrnItem::class, 'grn_item_id');
    }

    public function grnItemDetail()
    {
        return $this->belongsTo(MasGrnItemDetail::class, 'grn_item_detail_id');
    }

    public function assetCommissions() //asset comm
    {
        return $this->hasMany(AssetCommissionApplication::class, 'requisition_detail_id');
    }

    public function assetTransfers()
    {
        return $this->hasMany(AssetTransferApplication::class, 'requisition_detail_id');
    }

    public function assetReturns()
    {
        return $this->hasMany(AssetReturnApplication::class, 'requisition_detail_id');
    }

    public function site()
    {
        return $this->belongsTo(MasSite::class, 'site_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }

    public function office()
    {
        return $this->belongsTo(MasOffice::class, 'office_id');
    }
}
