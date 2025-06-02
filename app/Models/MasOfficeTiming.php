<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasOfficeTiming extends Model
{
    use HasFactory,CreatedByTrait;

    protected $table = 'mas_office_timings';

    protected $guarded = [];

    public function scopeFilter($query, $request)
    {
        if ($request->has('season') && $request->query('season') != '') {
            $query->where('season', 'LIKE', '%' . $request->query('season') . '%');
        }
    }
}
