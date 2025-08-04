<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasSite extends Model
{
    use HasFactory;

    protected $fillable = [ 'code', 'name', 'description', 'dzongkhag_id'];

    public function reqDetail()
    {
        return $this->hasOne(RequisitionDetail::class, 'site_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }
}
