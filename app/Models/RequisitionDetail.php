<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id', 'purchase_order_no', 'item_description', 'uom', 'store', 'stock_status', 'quantity_required', 'dzongkhag', 'site_name', 'remark'
    ];

    public function requisition()
    {
        return $this->belongsTo(RequisitionApplication::class, 'requisition_id');
    }
}
