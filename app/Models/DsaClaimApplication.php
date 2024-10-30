<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsaClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $cast = [
        'attachment' => 'array'
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function dsaClaimDetails() {
        return $this->hasMany(DsaClaimDetail::class, 'dsa_claim_id');
    }
}
