<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReturnDetail extends Model
{
    use HasFactory;

    protected $table = "return_details";

    public function assetReturn()
    {
        return $this->belongsTo(AssetReturnApplication::class, 'asset_return_id');
    }
}
