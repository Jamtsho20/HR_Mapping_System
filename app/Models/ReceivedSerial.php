<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedSerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_detail_id',
        'asset_serial_no',
        'asset_description',
        'is_commissioned'
    ];

    public function requisitionDetail()
    {
        return $this->belongsTo(RequisitionDetail::class);
    }

}
