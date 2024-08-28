<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasRegionLocation extends Model
{
    use HasFactory, CreatedByTrait;
    protected $table = 'mas_region_locations';

    protected $fillable = [
        'mas_region_id',
        'name',
        'mas_dzongkhag_id',
        'status',
    ];
    public function region()
    {
        return $this->belongsTo(MasRegion::class, 'mas_region_id');
    }
    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'mas_dzongkhag_id');
    }
    
    public function scopeFilter($query, $criteria)
    {
        // Example implementation
        return $query->where('name', 'like', "%$criteria%");
    }
    
}
