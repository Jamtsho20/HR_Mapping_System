<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TravelAuthorizationDetails;

class TravelAuthorization extends Model
{
    use HasFactory;

    protected $table = 'travel_authorizations';

    protected $fillable = [
       'travel_authorization_no',
        'date',
        'created_by',
        'updated_by',
        'status',
        'estimated_travel_expenses',
        'advance_amount',
        'daily_allowance',
        'travel_authorization_no'
    ];


    public function employee(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details(){
        return $this->hasMany(TravelAuthorizationDetails::class);
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function getStatusNameAttribute() {
        $statusNameMapping = config('global.application_status');
        return $statusNameMapping[$this->status] ?? config('global.null_value');
    }


    public function scopeFilter($query, $request){
    if ($request->has('mode_of_travel') && $request->query('mode_of_travel') != '') {
        $query->where('mode_of_travel', '=', $request->mode_of_travel);
        
    }

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('from_date', [$request->from_date, $request->to_date]);
    }
    elseif ($request->filled('from_date')) {
        $query->where('from_date', '=', $request->from_date);
    }
    // elseif ($request->filled('to_date')) {
    //     $query->where('date', '<=', $request->to_date); 
    // }
    }



}
