<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTransferApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function transferType()
    {
        return $this->belongsTo(MasTransferType::class, 'transfer_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function details ()
    {
        return $this->hasMany(AssetTransferDetail::class, 'asset_transfer_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
