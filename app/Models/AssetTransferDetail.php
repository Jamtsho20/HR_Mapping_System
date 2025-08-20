<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTransferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_transfer_id',
        'mas_asset_id'
    ];
    public function assetTransfer()
    {
        return $this->belongsTo(AssetTransferApplication::class, 'asset_transfer_id');
    }

    public function asset()
    {
        return $this->belongsTo(MasAssets::class, 'mas_asset_id');
    }

    // public function receivedSerial(){
    //     return $this->belongsTo(ReceivedSerial::class, 'received_serial_id');
    // }

    public function scopeReceivedSerial($query)
    {
        return $query->whereNotNull('received_serial_id'); // Or whatever condition makes sense
    }

}
