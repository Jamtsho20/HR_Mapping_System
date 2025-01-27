<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'store_id', 'item_category', 'item_number', 'item_description', 'uom', 'quantity', 'status'
    ];
}
