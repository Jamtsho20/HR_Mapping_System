<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseFuelClaimDetail extends Model
{
    use HasFactory;

    public function expFuelClaimApplication() {
        return $this->belongsTo(ExpenseFuelClaimApplication::class, 'exp_fuel_claim_id');
    }
}
