<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class SubStoreMaster extends Model
{
    use HasFactory, CreatedByTrait;

     //accessors & mutators
     public function setStoreNameAttribute($value)
     {
         $this->attributes['store_name'] = ucwords($value);    
         $this->attributes['location'] = ucwords($value);    

     }

    public function scopeFilter($query, $request)
    {
        if ($request->has('store_name') && $request->query('store_name') !== '') {
            $query->where('store_name', 'LIKE', '%' . $request->query('store_name') . '%');
        }
        
    }
}
