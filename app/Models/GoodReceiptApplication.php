<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GoodReceiptApplicationDetail;

class GoodReceiptApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function receiptType ()
    {
        return $this->belongsTo(MasGoodReceiptType::class, 'receipt_type_id');
    }
    public function detail ()
    {
        return $this->hasMany(GoodReceiptApplicationDetail::class, 'good_receipt_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
