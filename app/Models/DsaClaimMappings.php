<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class DsaClaimMappings extends Model
{
    use HasFactory, CreatedByTrait;

    protected $guarded = [];

    protected $fillable = [
        'travel_authorization_id',
        'dsa_claim_id',
        'advance_application_id',
        'ta_amount',
        'advance_amount',
        'attachment',
        'number_of_days'
    ];

    public function dsaDetails()
    {
        return $this->hasMany(DsaClaimDetail::class, 'dsa_map_id');
    }

    public function travelAuthorization()
    {
        return $this->belongsTo(TravelAuthorizationApplication::class, 'travel_authorization_id');
    }

    // Relationship with Advance Application
    public function advanceApplication()
    {
        return $this->belongsTo(AdvanceApplication::class, 'advance_application_id');
    }

    // Relationship with DSA Claim Application
    public function dsaClaimApplication()
    {
        return $this->belongsTo(DsaClaimApplication::class, 'dsa_claim_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
