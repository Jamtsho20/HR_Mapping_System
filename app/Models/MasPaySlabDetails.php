<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;

class MasPaySlabDetails extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'mas_pay_slab_id', 'pay_from', 'pay_to', 'amount'
    ];
    
    public function masPaySlab(){
        return $this->belongsTo(MasPaySlab::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('pay_from') && $request->query('pay_from') != '') {
            $query->where('pay_from', '>=', $request->query('pay_from'));
        }

        if ($request->has('pay_to') && $request->query('pay_to') != '') {
            $query->where('pay_to', '<=', $request->query('pay_to'));
        }

        if ($request->has('amount') && $request->query('amount') != '') {
            $query->where('amount', $request->query('amount'));
        }
    }
}
