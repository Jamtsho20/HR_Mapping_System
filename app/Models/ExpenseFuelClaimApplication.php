<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseFuelClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $cast = ['attachment' => 'array'];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function expenseFuelDetails() {
        return $this->hasMany(ExpenseFuelClaimDetail::class, 'exp_claim_id');
    }
}
