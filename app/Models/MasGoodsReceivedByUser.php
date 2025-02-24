<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGoodsReceivedByUser extends Model
{
    protected $fillable = [
        'requisition_application_id',
        'total_requested_quantity',
        'total_received_quantity',
        'received_from',
        'received_by',
        'doc_no'
    ];
    
    use HasFactory, CreatedByTrait;

    // public function histories()
    // {
    //     return $this->morphMany(ApplicationHistory::class, 'application');
    // }

    // public function receiptType ()
    // {
    //     return $this->belongsTo(MasGoodReceiptType::class, 'receipt_type_id');
    // }
    public function receivedDetail ()
    {
        return $this->hasMany(GoodsReceivedDetail::class, 'good_receipt_id');
    }

}
