<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasSite extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [ 'code', 'name', 'description', 'created_by', 'updated_by'];

    public function reqDetail()
    {
        return $this->hasOne(RequisitionDetail::class, 'site_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }
}
