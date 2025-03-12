<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGrnItemDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'store_id',
        'grn_id',
        'quantity',
        'description',
        'status'
    ];

    public function grn()
   {
        return $this->belongsTo(MasGrnItem::class, 'grn_id');
   }
    public function store()
   {
        return $this->belongsTo(MasStore::class, 'store_id');
   }

   public function item()
   {
        return $this->belongsTo(MasItem::class, 'item_id');
   }
   public function scopeFilter($query, $request)
   {
        if ($request->has('item_id') && $request->query('item_id') != '') {
            $query->where('item_id', $request->query('item_id'));
        }

        if ($request->has('store_id') && $request->query('store_id') != '') {
            $query->where('store_id', $request->query('store_id'));
        }

        if ($request->has('item_description') && $request->query('item_description') != '') {
            $query->where('item_description', $request->query('item_description'));
        }

        if ($request->has('grn_no') && $request->query('grn_no') != '') {
            $query->where('grn_no', $request->query('grn_no'));
        }
   }
}
