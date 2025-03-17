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

    // Link to GrnItemMapping
    public function grnItemMapping()
    {
        return $this->belongsTo(MasGrnItems::class, 'grn_item_id');
    }

    public function itemMappingDetail()
    {
        return $this->belongsTo(MasGrnItemDetail::class, 'grn_item_detail_id');
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
        });
    }
}
