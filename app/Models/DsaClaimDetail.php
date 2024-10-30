<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsaClaimDetail extends Model
{
    use HasFactory;

    public function dsaClaimApplication() {
        return $this->belongsTo(DsaClaimApplication::class, 'dsa_claim_id');
    }
}
