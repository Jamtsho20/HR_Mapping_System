<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasStore extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $fillable = [
        'name',
        'location',
        'status',
        'created_by',
        'updated_by',
    ];

    public function subStores()
    {
        return $this->hasMany(MasSubStore::class, 'mas_stores_id');
    }

     //accessors & mutators
     public function scopeFilter($query, $request)
     {
         if ($request->has('store_name') && $request->query('store_name') != '') {
             $query->where('store_name', 'LIKE', '%' . $request->query('store_name') . '%');
         }
         
         if ($request->has('location') && $request->query('location') != '') {
             $query->where('location', 'LIKE', '%' . $request->query('location') . '%');
         }
 
         if ($request->has('status') && $request->query('status') != '') {
             $query->where('status', $request->query('status'));
         }
    }
}
