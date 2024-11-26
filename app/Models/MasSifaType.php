<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasSifaType extends Model
{
    use HasFactory, CreatedByTrait;

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function sifaRegistration()
        {
            return $this->hasMany(SifaRegistration::class, 'sifa_registration_id');
        }
}
