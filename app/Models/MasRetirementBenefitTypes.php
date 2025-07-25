<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasRetirementBenefitTypes extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'benefit_types';

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }
    public function retirementBenefitNomination()
    {
        return $this->hasMany(RetirementBenefit::class, 'retirement_benefit_id');
    }
}
