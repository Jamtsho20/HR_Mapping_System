<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasSubStore extends Model
{
    use HasFactory,CreatedByTrait;

    protected $fillable = [
        'mas_stores_id',  
        'name',
        'location',
        'status',
        'created_by',
        'updated_by',
    ];
    public function store()
    {
        return $this->belongsTo(MasStore::class, 'mas_stores_id'); 
    }
}
