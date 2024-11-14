<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelAuthorizationDetails extends Model
{
    use HasFactory;

    protected $table = 'travel_authorization_details';

    protected $fillable = [
        'travel_authorization_no',
        'mode_of_travel',
        'from_location',
        'to_location',
        'from_date',
        'to_date',
        'purpose',
        'advance_amount',
        'created_by',
        'updated_by',
        'status',
        'daily_allowance'

    ];



    public function travelAuthorization()
    {
        return $this->belongsTo(TravelAuthorization::class);
    }
}
