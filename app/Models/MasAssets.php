<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Models\MasItem;
use App\Models\User;
use App\Models\MasSite;
use App\Models\CommissionDetail;
use App\Models\AssetTransferDetail;
use App\Models\AssetReturnDetail;
use App\Models\SapAsset;



class MasAssets extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [ 'serial_number',
    'current_employee_id',
    'item_id',
    'current_site_id',
    'received_serial_id',
    'commission_detail_id',
    'asset_transfer_detail_id',
    'return_detail_id',
    'initial_owner_id',
    'created_by',
    'updated_by',
    'status',
    'asset_type',
    'asset_transfer_id',
    'sap_asset_id',
    'prj_line_num',
    'emp_line_num',
    'is_transfered',
    'is_returned',
    'asset_no'
];



    public function sapAssets()
    {
        return $this->belongsTo(SapAsset::class, 'sap_asset_id');
    }
    public function receivedSerial()
    {
        return $this->belongsTo(ReceivedSerial::class, 'received_serial_id');
    }
    public function item()
    {
        return $this->belongsTo(MasItem::class, 'item_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'current_employee_id');
    }

    public function site()
    {
        return $this->belongsTo(MasSite::class, 'current_site_id');
    }

    public function commissionDetail()
    {
        return $this->belongsTo(CommissionDetail::class, 'commission_detail_id');
    }

    public function assetTransferDetail()
    {
        return $this->belongsTo(AssetTransferDetail::class, 'asset_transfer_detail_id');
    }

    public function returnDetail()
    {
        return $this->belongsTo(AssetReturnDetail::class, 'return_detail_id');
    }

    public function initialOwner()
    {
        return $this->belongsTo(User::class, 'initial_owner_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('serial_number') && $request->query('serial_number') != '') {
            $query->where('serial_number', 'LIKE', '%' . $request->query('serial_number') . '%');
        }

        if ($request->has('current_site_id') && $request->query('current_site_id') != '') {
            $query->where('current_site_id', $request->query('current_site_id'));
        }
    }

    protected static function boot()
{
    parent::boot();

    static::created(function ($asset) {
        // Log the initial asset creation
        MasAssetLog::create([
            'asset_id' => $asset->id,
            'current_employee_id' => $asset->current_employee_id,
            'current_site_id' => $asset->current_site_id,
            'asset_transfer_detail_id' => $asset->asset_transfer_detail_id,
            'return_detail_id' => $asset->return_detail_id,
            'current_depreciation' => $asset->current_depreciation
        ]);
    });

    static::updated(function ($asset) {
        // Log when key fields are updated
        if ($asset->wasChanged(['current_employee_id', 'current_site_id', 'asset_transfer_detail_id', 'return_detail_id', 'current_depreciation', 'item_code', 'description', 'quantity', 'amount'])) {
            MasAssetLog::create([
                'asset_id' => $asset->id,
                'current_employee_id' => $asset->current_employee_id,
                'current_site_id' => $asset->current_site_id,
                'asset_transfer_detail_id' => $asset->asset_transfer_detail_id,
                'return_detail_id' => $asset->return_detail_id,
                'current_depreciation' => $asset->current_depreciation
            ]);
        }
    });
}


}
