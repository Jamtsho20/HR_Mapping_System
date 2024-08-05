<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;

class MasPaySlab extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $casts = [
        'effective_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeFilter($query, $request)
    {
    if ($request->has('name') && $request->query('name') != '') {
        $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
    }

    if ($request->has('effective_date') && $request->query('effective_date') != '') {
        $query->whereDate('effective_date', $request->query('effective_date'));
    }

    if ($request->has('formula') && $request->query('formula') != '') {
        $query->where('formula', 'LIKE', '%' . $request->query('formula') . '%');
    }
    }
}
