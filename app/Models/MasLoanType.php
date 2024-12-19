<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasLoanType extends Model
{
    use HasFactory,CreatedByTrait;
    protected $fillable = [
        'name',
    ];

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '')
        {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
