<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasStore extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'parent_store_id',
        'name',
        'code',
        'store_location',
        'store_email',
        'phone_number',
        'contact_person',
        'contact_email',
        'contact_number',
        'country_id',
        'dzongkhag_id',
        'region_id',
        'status',
        'created_by',
        'updated_by',
    ];

    public function subStores()
    {
        return $this->hasMany(MasStore::class, 'parent_store_id');
    }

     //accessors & mutators
     public function scopeFilter($query, $request)
     {
         if ($request->has('store_name') && $request->query('store_name') != '') {
             $query->where('store_name', 'LIKE', '%' . $request->query('store_name') . '%');
         }
         
         if ($request->has('store_location') && $request->query('store_location') != '') {
             $query->where('store_location', 'LIKE', '%' . $request->query('store_location') . '%');
         }
 
         if ($request->has('status') && $request->query('status') != '') {
             $query->where('status', $request->query('status'));
         }
    }
}
