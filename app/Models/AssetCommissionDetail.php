<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCommissionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'commission_id', 'purchase_order_no', 'item_description', 'uom',  'quantity', 'date_placed_in_service', 'dzongkhag', 'site_name','remark', 'status'
    ];

    public function assetCommission()
    {
        return $this->belongsTo(AssetCommissionApplication::class, 'commission_id');
    }
}
