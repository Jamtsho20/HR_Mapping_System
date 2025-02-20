<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceivedDetail extends Model
{
    use HasFactory;

    public function itemSerials()
    {
        return $this->hasMany(GoodsReceivedItemSerial::class, 'received_detail_id');
    }

}
