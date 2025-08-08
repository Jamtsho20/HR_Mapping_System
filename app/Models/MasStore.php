<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasStore extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'parent_store_id',
        'name',
        'code',
        'country',
        'dzongkhag',
        'region',
        'store_email',
        'phone_number',
        'contact_person',
        'contact_email',
        'status',
        'created_by',
        'updated_by',
        'store_incharge',
    ];

    public function subStores()
    {
        return $this->hasMany(MasStore::class, 'parent_store_id');
    }

    public function storeIncharge()
    {
        return $this->belongsTo(User::class, 'store_incharge');
    }

    public function grnItems()
    {
        return $this->hasMany(GrnItemMapping::class, 'store_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

     //accessors & mutators
     public function scopeFilter($query, $request)
     {
         if ($request->has('name') && $request->query('name') != '') {
             $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
         }

         if ($request->has('code') && $request->query('code') != '') {
             $query->where('code', $request->query('code'));
         }

         if ($request->has('dzongkhag') && $request->query('dzongkhag') != '') {
             $query->where('dzongkhag', 'LIKE', '%' . $request->query('dzongkhag') . '%');
         }

         if ($request->has('region') && $request->query('region') != '') {
             $query->where('region', 'LIKE', '%' . $request->query('region') . '%');
         }

    }
}
