<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Models\MasRegion;

class WorkHolidayList extends Model
{
    use HasFactory, CreatedByTrait;

    protected $casts = [
        'region_id' => 'array',
    ];

    //accessors & mutators
    //get the name of the regions stored as IDs in the DB
    public function getRegionNameAttribute()
    {
        return MasRegion::whereIn('id', $this->region_id)->pluck('name')->toArray();
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('year') && $request->query('year') != '') {
            $query->whereRaw('YEAR(start_date) = ?',$request->query('year'));
        }
    }
}
