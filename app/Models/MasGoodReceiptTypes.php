<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGoodReceiptTypes extends Model
{
    use HasFactory, CreatedByTrait;

    public function approvableRule () // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function goodReceipts () 
    {
        return $this->hasMany(GoodReceiptApplication::class, 'receipt_type_id');
    }

}
