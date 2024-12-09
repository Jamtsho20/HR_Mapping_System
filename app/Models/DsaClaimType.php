<?php

namespace App\Models;

use App\Models\MasApprovalRule;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsaClaimType extends Model
{
    use HasFactory, CreatedByTrait;


    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');//
    }
}
