<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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


    public function office()
    {
        return $this->belongsTo(MasOffice::class, 'office_id');
    }
    public function site()
    {
        return $this->belongsTo(MasSite::class, 'site_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($commissionDetail) {
            // Update the is_commissioned column in received_serials
            // DB::table('received_serials')
            //     ->where('id', $commissionDetail->received_serial_id) // Ensure this column exists in commissionDetail
            //     ->update([
            //         'is_commissioned' => 1,
            //         'updated_at' => now()
            //     ]);

            // $commissionDetail->loadMissing('receivedSerial.requisitionDetail');
            // \App\Models\MasAssets::create([
            //     'serial_number' => $commissionDetail->receivedSerial->asset_serial_no,
            //     'current_employee_id' => $commissionDetail->assetCommission->created_by,
            //     'item_id' => $commissionDetail->receivedSerial->requisitionDetail->item_id ?? $commissionDetail->receivedSerial->requisitionDetail->grnItemDetail->item->id,
            //     'current_site_id' => $commissionDetail->site_id,
            //     'received_serial_id' => $commissionDetail->received_serial_id,
            //     'commission_detail_id' => $commissionDetail->id,
            //     'initial_owner_id' => $commissionDetail->assetCommission->created_by
            // ]);
        });
    }
}
