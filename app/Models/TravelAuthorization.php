<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelAuthorization extends Model
{
    use HasFactory;

    protected $table = 'travel_authorizations';

    protected $fillable = [
        'date',
        'mode_of_travel',
        'from_location',
        'to_location',
        'from_date',
        'to_date',
        'estimated_travel_expenses',
        'advance_amount',
        'purpose',
        'created_by',
        'updated_by',
        'status',
        'daily_allowance'
    ];

    public function employee(){
        return $this->belongsTo(User::class, 'created_by');
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
