<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id', 'grn_item_mapping_id', 'grn_no', 'uom', 'item_description', 'store_id', 'quantity_required', 'dzongkhag', 'office_id', 'site_id', 'remark'
    ];

    public function requisition()
    {
        return $this->belongsTo(RequisitionApplication::class, 'requisition_id');
    }

    public function site()
    {
        return $this->belongsTo(MasSite::class, 'site_id');
    }

    public function office()
    {
        return $this->belongsTo(MasOffice::class, 'office_id');
    }
}
