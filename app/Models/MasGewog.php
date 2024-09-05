<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Models\MasDzongkhag;

class MasGewog extends Model
{
    use HasFactory, CreatedByTrait;

    public function setGewogAttribute($value)
    {
        $this->attributes['gewog'] = ucwords($value);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('gewog') && $request->query('gewog') != '')
        {
            $query->where('name', 'LIKE', '%' . $request->query('gewog') . '%');
        }
        if ($request->has('dzongkhag') && $request->query('dzongkhag') != '')
        {
            $query->where('mas_dzongkhag_id', $request->query('dzongkhag'));
        }
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'mas_dzongkhag_id');
    }

    public function villages()
    {
        return $this->hasMany(MasVillage::class, 'mas_gewog_id');
    }
    
}
