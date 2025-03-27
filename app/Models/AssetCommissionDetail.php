<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCommissionDetail extends Model
{
    use HasFactory;

    protected $table = 'commission_details';
    
    protected $fillable = [
        'commission_id', 
        'received_serial_id', 
        'date_placed_in_service', 
        'dzongkhag_id', 
        'office_id',
        'site_id',
        'remark',
    ];

    public function assetCommission()
    {
        return $this->belongsTo(AssetCommissionApplication::class, 'commission_id');
    }

    public function receivedSerial(){
        return $this->belongsTo(ReceivedSerial::class, 'received_serial_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }


    public function site()
    {
        return $this->belongsTo(MasSite::class, 'site_id');
    }
}
