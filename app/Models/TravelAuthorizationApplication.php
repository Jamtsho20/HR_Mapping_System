<?php

namespace App\Models;

use App\Models\MasTravelType;
use App\Models\TravelAuthorizationDetails;
use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelAuthorizationApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'travel_authorization_applications';

    protected $fillable = [
        'transaction_no',
        'date',
        'type_id',
        'created_by',
        'updated_by',
        'status',
        'estimated_travel_expenses',
        'advance_amount',
        'daily_allowance',
    ];
    protected $cast = [
        'date' => 'date',
    ];

    public function employee(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function advance()
    {
        return $this->hasOne(AdvanceApplication::class, 'travel_authorization_id');
    }

    public function details()
{
    return $this->hasMany(TravelAuthorizationDetails::class, 'travel_authorization_id');
}

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function travelType()
    {
        return $this->belongsTo(MasTravelType::class, 'type_id');
    }

    public function type()
    {
        return $this->belongsTo(MasTravelType::class, 'type_id');
    }

     public function dsaadvance() {
        return $this->hasMany(DsaClaimApplication::class, 'travel_authorization_id');
     }

     public function travel_approved_by()
     {
         return $this->belongsTo(User::class, 'updated_by');
     }

    //accessors and mutations
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

    // scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true){
        if ($request->has('status') && $request->query('status') !== '') {
            $query->where('status', '=', $request->query('status'));
        }


        // if ($request->filled('from_date') && $request->filled('to_date')) {
        //     $query->whereBetween('date', [$request->from_date, $request->to_date]);
        // }
        // elseif ($request->filled('from_date')) {
        //     $query->where('date', '=', $request->from_date);
        // }

        if($onesOwnRecord){
            $query->where('created_by', auth()->user()->id);
        }
        if ($request->get('year')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('year'));

            // Step 2: Convert each date to Y-m format using Carbon
            $startDate = Carbon::createFromFormat('Y-m', trim($dates[0]));

            // Extract year and month
            $year = $startDate->year;
            $month = $startDate->month;

            // Filter by year and month
            $query->whereYear('date', $year)
            ->whereMonth('date', $month);
        }
    // elseif ($request->filled('to_date')) {
    //     $query->where('date', '<=', $request->to_date);
    // }

    if ($request->filled('travel_type')) {
        $query->whereHas('travelType', function ($subQuery) use ($request) {
            $subQuery->where('name', 'like', '%' . $request->travel_type . '%');
        });
    }

    if ($request->has('name') && $request->get('name') != '') {
        $query->whereHas('employee', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->get('name') . '%');
        });
    }
    }

}
