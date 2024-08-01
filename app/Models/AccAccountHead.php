<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;

class AccAccountHead extends Model
{
    use HasFactory, CreatedByTrait;

    /**
     * Scope a query to filter by account head name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        if ($request->has('account_head_name') && $request->query('account_head_name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('account_head_name') . '%');
        }
        
        if ($request->has('type') && $request->query('type') != '') {
            $query->where('type', $request->query('type'));
        }
    }

}
