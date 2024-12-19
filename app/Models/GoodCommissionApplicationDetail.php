<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodCommissionApplicationDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'good_commission_id', 'purchase_order_no', 'item_description', 'uom',  'quantity', 'date_placed_in_service', 'dzongkhag', 'site_name','remark', 'status'
    ];

    public function goodCommission()
    {
        return $this->belongsTo(GoodCommissionApplication::class, 'good_issue_id');
    }
}
