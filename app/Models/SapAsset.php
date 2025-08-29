<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
class SapAsset extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
                   'item_id',
                   'serial_number',
                   'uom',
                   'grn_number',
                   'item_description',
                   'category',
                   'quantity',
                   'amount',
                   'capitalization_date',
                   'end_date',
                   'created_by',
                   'updated_by',
               ];

    public function item()
    {
        return $this->belongsTo(MasItem::class, 'item_id');
    }
}
