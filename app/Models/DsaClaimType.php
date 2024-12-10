<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Models\AssetTransferApplication;
use App\Models\MasApprovalRule;

class DsaClaimType extends Model
{
    use HasFactory, CreatedByTrait;

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function assetTransfers()
    {
        return $this->hasMany(AssetTransferApplication::class, 'transfer_type_id');
    }

 
}
