<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;

class MasPayHead extends Model
{
    use HasFactory, CreatedByTrait;

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }

        if ($request->has('payhead_type') && $request->query('payhead_type') != '') {
            $query->where('payhead_type', $request->query('payhead_type'));
        }

        if ($request->has('accounthead_type') && $request->query('accounthead_type') != '') {
            $query->where('accounthead_type', $request->query('accounthead_type'));
        }

        if ($request->has('calculation_method') && $request->query('calculation_method') != '') {
            $query->where('calculation_method', $request->query('calculation_method'));
        }

        if ($request->has('calculated_on') && $request->query('calculated_on') != '') {
            $query->where('calculated_on', $request->query('calculated_on'));
        }

        if ($request->has('formula') && $request->query('formula') != '') {
            $query->where('formula', 'LIKE', '%' . $request->query('formula') . '%');
        }
    }
}
