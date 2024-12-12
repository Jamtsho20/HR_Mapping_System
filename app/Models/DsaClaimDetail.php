<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsaClaimDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dsa_claim_id',
        'from_date',
        'to_date',
        'from_location',
        'to_location',
        'total_days',
        'daily_allowance',
        'travel_allowance',
        'total_amount',
        'remark',
    ];

    public function dsaClaimApplication()
    {
        return $this->belongsTo(DsaClaimApplication::class, 'dsa_claim_id');
    }
}
