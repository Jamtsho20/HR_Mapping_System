<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class AdvanceApplication extends Model
{
    use HasFactory,CreatedByTrait;
    
    protected $fillable = [
        'advance_no',
        'date',
        'advance_type_id',
        'travel_authorization_id',
        'mas_employee_id',
        'mode_of_travel',
        'from_location',
        'to_location',
        'advance_settlement_date',
        'from_date',
        'to_date',
        'amount',
        'remark',
        'attachment',
        'interest_rate',
        'total_amount', 
        'no_of_emi',
        'monthly_emi_amount',
        'deduction_from_period',
        'item_type',
        'status',
        
    ];
    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function employee(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function advanceType()
    {
        return $this->belongsTo(MasAdvanceTypes::class, 'advance_type_id');
    }

    public function advanceDetails()
    {
        return $this->hasMany(AdvanceDetail::class, 'advance_application_id');
    }

    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true){
        if ($request->has('advance_type') && $request->query('leave_type') != '') {
            $query->where('mas_advance_type_id', $request->query('advance_type'));
        }

        if($onesOwnRecord){
            $query->where('created_by', auth()->user()->id);
        }
    }

    public function setDeductionFromPeriodAttribute($value)
    {
        $this->attributes['deduction_from_period'] = Carbon::parse($value)->format('Y-m-01');
    }

    public function getStatusNameAttribute() {
        $statusNameMapping = config('global.application_status');
        return $statusNameMapping[$this->status] ?? config('global.null_value');
    }
}
