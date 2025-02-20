<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGoodsReceivedByUser extends Model
{
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
