<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasOffice extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'name','mas_dzongkhag_id'
    ];

    
    //relationship
    
    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '')
        {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
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
}
