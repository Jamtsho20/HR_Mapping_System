<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasAssetLog extends Model
{
    use HasFactory;

    public $timestamps = false;
     protected $fillable = [
        'asset_id',
        'current_employee_id',
        'current_site_id',
        'asset_transfer_detail_id',
        'return_detail_id'
    ];
}
