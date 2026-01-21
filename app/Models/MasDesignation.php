<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasDesignation extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = [
        'mas_function_id',
        'name',
        'status',
    ];


    public function scopeFilter($query, $request)
    {
        if ($request->has('designation') && $request->query('designation') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('designation') . '%');
        }
    }
    public function function()
    {
        return $this->belongsTo(FunctionModel::class, 'mas_function_id');
    }
}
