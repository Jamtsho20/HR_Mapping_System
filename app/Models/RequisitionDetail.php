<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_mapping_id', 'requested_quantity', 'received_quantity', 'commissioned_quantity', 'status', 'item_mapping_detail_id',  'site_id', 'dzongkhag_id', 'office_id', 'remark'
    ];


    public function grnMapping()
    {
        return $this->belongsTo(GrnItemMapping::class, 'grn_item_mapping_id');
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
