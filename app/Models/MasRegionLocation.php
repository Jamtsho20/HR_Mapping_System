<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasRegionLocation extends Model
{
    use HasFactory, CreatedByTrait;
    public function region()
    {
        return $this->belongsTo(MasRegion::class, 'mas_region_id');
    }
    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'mas_dzongkhag_id');
    }
}
