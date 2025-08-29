<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasItem extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'item_no',
        'is_fixed_asset',
         'item_group',
         'item_description',
         'item_group_id',
         'uom',
         'status',
         'item_no_old',
         'item_group_id',
    ];

    public function grnItems()
    {
        return $this->hasMany(GrnItemMapping::class, 'item_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function scopeFilter($query, $request)
     {
         if ($request->has('item_no') && $request->query('item_no') != '') {
             $query->where('item_no', $request->query('item_no'));
         }

         if ($request->has('item_description') && $request->query('item_description') != '') {
             $query->where('item_description', $request->query('item_description'));
         }

    }
}
