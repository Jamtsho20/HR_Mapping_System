<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Models\MasGewog;

class MasDzongkhag extends Model
{
    use HasFactory, CreatedByTrait;
     //accessors & mutators
     public function setDzongkhagAttribute($value)
     {
         $this->attributes['dzongkhag'] = ucwords($value);
     }

    public function scopeFilter($query, $request)
    {
        if ($request->has('dzongkhag') && $request->query('dzongkhag') != '') {
            $query->where('dzongkhag', 'LIKE', '%' .$request->query('dzongkhag') . '%');
        }
    }

    public function gewogs()
    {
        return $this->hasMany(MasGewog::class, 'mas_dzongkhag_id');
    }
    public function empPresentAddress()
    {
        return $this->belongsTo(MasEmployeePresentAddress::class, 'id');
    }
    public function empPermanentAddress()
    {
        return $this->belongsTo(MasEmployeePermenantAddress::class, 'id');
    }
}
