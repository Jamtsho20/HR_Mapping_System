<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelAuthorizationDetails extends Model
{
    use HasFactory;

    protected $table = 'travel_authorization_details';

    protected $fillable = [
        'travel_authorization_id',
        'mode_of_travel',
        'from_location',
        'total_days',
        'to_location',
        'from_date',
        'to_date',
        'purpose',
        'created_by',
        'updated_by',
        'status',
        'daily_allowance'

    ];



    public function travelAuthorization()
    {
        return $this->belongsTo(TravelAuthorizationApplication::class, 'travel_authorization_id');
    }

    //accessors and mutators
    public function getTravelNameAttribute() {
        $travelNameMapping = config('global.travel_modes');
        return $travelNameMapping[$this->mode_of_travel] ?? config('global.null_value');
    }
}
