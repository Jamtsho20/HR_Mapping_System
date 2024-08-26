<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasOffice extends Model
{
    use HasFactory, CreatedByTrait;
    
    //relationship

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'mas_dzongkhag_id'); 
    }
}
