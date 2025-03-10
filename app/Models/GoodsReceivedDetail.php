<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceivedDetail extends Model
{
    use HasFactory;

    public function itemSerials()
    {
        return $this->hasMany(GoodsReceivedDetailSerial::class, 'received_detail_id');
    }

    public function commissions()
    {
        return $this->hasMany(GoodsReceivedDetail::class, 'goods_received_detail_id');
    }


}
