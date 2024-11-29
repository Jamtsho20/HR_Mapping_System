<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function receiptType ()
    {
        return $this->belongsTo(MasGoodReceiptTypes::class, 'receipt_type_id');
    }
}
