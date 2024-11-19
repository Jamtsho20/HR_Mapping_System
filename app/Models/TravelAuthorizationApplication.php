<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\TravelAuthorizationDetails;

class TravelAuthorizationApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'travel_authorization_applications';

    protected $fillable = [
       'travel_authorization_no',
        'date',
        'created_by',
        'updated_by',
        'status',
        'estimated_travel_expenses',
        'advance_amount',
        'daily_allowance',
        'travel_type_id',
    ];


    public function employee(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
{
    return $this->hasMany(TravelAuthorizationDetails::class, 'travel_authorization_id');
}

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

   

    public function getStatusNameAttribute() {
        $statusNameMapping = config('global.application_status');
        return $statusNameMapping[$this->status] ?? config('global.null_value');
    }

    protected static function boot()
{
    parent::boot();

    static::deleting(function ($travelAuthorization) {
        $travelAuthorization->details()->delete();
    });
}

public function travelType()
{
    return $this->belongsTo(MasTravelType::class, 'travel_type_id');
}


    public function scopeFilter($query, $request){
    if ($request->has('status') && $request->query('status') != '') {
        $query->where('status', '=', $request->mode_of_travel);
        
    }

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }
    elseif ($request->filled('from_date')) {
        $query->where('date', '=', $request->from_date);
    }
    // elseif ($request->filled('to_date')) {
    //     $query->where('date', '<=', $request->to_date); 
    // }

    if ($request->filled('travel_type')) {
        $query->whereHas('travelType', function ($subQuery) use ($request) {
            $subQuery->where('name', 'like', '%' . $request->travel_type . '%');
        });
    }
    }



}
