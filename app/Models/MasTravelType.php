<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;


class MasTravelType extends Model
{
    use HasFactory, CreatedByTrait;

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function travelAuthorizations()
        {
            return $this->hasMany(TravelAuthorizationApplication::class, 'travel_type_id');
        }
}
