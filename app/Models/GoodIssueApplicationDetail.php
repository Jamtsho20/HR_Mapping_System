<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GoodIssueApplication;

class GoodIssueApplicationDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'good_issue_id', 'purchase_order_no', 'item_description', 'uom', 'store', 'stock_status', 'receipt_quantity', 'dzongkhag', 'site_name','remark', 'status'
    ];

    public function goodIssue()
    {
        return $this->belongsTo(GoodIssueApplication::class, 'good_issue_id');
    }
}
