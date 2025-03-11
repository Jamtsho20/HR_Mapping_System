<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceivedDetailSerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_received_by_user_id',
        'req_detail_id',
        'grn_no',
        'uom',
        'serial_number',
    ];

    public function receivedDetail()
    {
        return $this->belongsTo(GoodsReceivedDetail::class, 'received_detail_id');
    }

}
