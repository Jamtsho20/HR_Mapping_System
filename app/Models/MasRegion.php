<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasRegion extends Model
{

    use HasFactory, CreatedByTrait;

    //relationships
    public function holidays()
    {
        return $this->hasMany(WorkHolidayList::class, 'mas_region_id');
    }

    //accessors & mutators
    public function setRegionNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    //scopes & filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('region') && $request->query('region') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('region') . '%');
        }
    }
}
