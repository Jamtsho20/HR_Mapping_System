<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReturnApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function type ()
    {
        return $this->belongsTo(MasCommissionTypes::class, 'commission_type_id');
    }
    
    public function details ()
    {
        return $this->hasMany(AssetCommissionDetail::class, 'commission_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
