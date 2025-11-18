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
        'mas_asset_id',
        'unit',
        'store_id',
        'condition_code',
        'remark'
    ];
    public function assetReturn()
    {
        return $this->belongsTo(AssetReturnApplication::class, 'asset_return_id');
    }

     public function asset()
    {
        return $this->belongsTo(MasAssets::class, 'mas_asset_id');
    }

    public function store()
    {
        return $this->belongsTo(MasStore::class, 'store_id');
    }

}
