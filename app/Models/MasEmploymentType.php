<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use Illuminate\Support\Facades\Route;

class MasEmploymentType extends Model
{
    use HasFactory, CreatedByTrait;

    //filters
    public function scopeFilter($query, $request)
    {
        $routeName = Route::currentRouteName();
        if ($request->has('employment_type') && $request->query('employment_type') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('employment_type') . '%');
        }

        if($routeName == 'employment-types.index') {
            $query->where('id', '<>', 0);
        }
    }
}
