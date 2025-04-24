<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReturnDetail extends Model
{
    use HasFactory;

    protected $table = "return_details";
    protected $fillable = [
        'asset_return_id',
        'received_serial_id',
        'unit',
        'dzongkhag_id',
        'store_id',
        'condition_code',
        'remark'
    ];
    public function assetReturn()
    {
        return $this->belongsTo(AssetReturnApplication::class, 'asset_return_id');
    }

    public function receivedSerial()
    {
        return $this->belongsTo(ReceivedSerial::class, 'received_serial_id');
    }

    
    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }


    public function store()
    {
        return $this->belongsTo(MasStore::class, 'store_id');
    }

}
