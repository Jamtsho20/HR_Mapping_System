<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasOffice extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'name'
    ];

    
    //relationship
    
    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '')
        {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'mas_dzongkhag_id'); 
    }
}
