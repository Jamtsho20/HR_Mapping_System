<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Models\MasGewog;

class MasVillage extends Model
{
    use HasFactory, CreatedByTrait;

    public function setVillageAttribute($value)
    {
        $this->attributes['village'] = ucwords($value);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('village') && $request->query('village') != '') {
            $query->where('village', 'LIKE', '%' . $request->query('village') . '%');
        }
        if ($request->has('gewog') && $request->query('gewog') != '')
        {
            $query->where('mas_gewog_id',   $request->query('gewog'));
        }

        if ($request->has('dzongkhag') && $request->query('dzongkhag') != '')
        {
            $query->join('mas_gewogs', 'mas_villages.mas_gewog_id', '=', 'mas_gewogs.id')
            ->where('mas_dzongkhag_id',   $request->query('dzongkhag'));
        }
        
    }

    public function gewogs()
    {
        return $this->belongsTo(MasGewog::class, 'mas_gewog_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class);
    }
    
}
