<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGoodsReceivedByUser extends Model
{
    protected $fillable = [
        'requisition_id',
        'total_requested_quantity',
        'total_received_quantity',
        'received_from',
        'received_by',
        'doc_no'
    ];
    
    use HasFactory, CreatedByTrait;

    public function details ()
    {
        return $this->hasMany(GoodsReceivedDetail::class, 'goods_received_by_user_id');
    }

    public function requisition () 
    {
        return $this->belongsTo(RequisitionApplication::class, 'requisition_id');
    }

    public function itemSerials()
    {
        return $this->hasManyThrough(
            GoodsReceivedDetailSerial::class, //final model
            GoodsReceivedDetail::class, //intermediate model
            'goods_received_by_user_id', //Foreign key on GoodsReceivedDetail (links to this model)
            'goods_received_detail_id', // Foreign key on GoodsReceivedDetailSerial
            'id', // Local key on MasGoodsReceivedByUser
            'id' // Local key on GoodsReceivedDetail
        );
    }

}
