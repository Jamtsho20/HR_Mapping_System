<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id', 'requested_quantity','is_received', 'received_quantity', 'item_id','commissioned_quantity', 'status', 'grn_item_id', 'grn_item_detail_id',  'site_id', 'dzongkhag_id', 'office_id', 'remark', 'store_id', 'current_stock', 'uom'
    ];

    public function unitOfMeasurement(){
        return $this->belongsTo(AssetUnitOfMeasurement::class, 'uom');
    }
    public function item(){
        return $this->belongsTo(MasItem::class, 'item_id');
    }

    public function store(){
        return $this->belongsTo(MasStore::class, 'store_id');
    }
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

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($requisitionDetail) {

            if ($requisitionDetail->received_quantity < $requisitionDetail->requested_quantity) {
                $remainingQty = $requisitionDetail->requested_quantity - $requisitionDetail->received_quantity;


                $grnItemDetail = MasGrnItemDetail::find($requisitionDetail->grn_item_detail_id);

                if ($grnItemDetail) {

                    $grnItemDetail->increment('quantity', $remainingQty);
                }
            }

            if ($requisitionDetail->received_quantity == 0) {
                 $requisitionDetail->updateQuietly(['is_received' => 1]);
            }
        });
    }
}
