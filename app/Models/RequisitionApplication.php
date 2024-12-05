<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'requisition_type_id',
        'requisition_no',
        'requisition_date',
        'asset_type',
        'need_by_date',
        'employee_id',
        'item_category',
        'status',

    ];

    public function requisitionType()
    {
        return $this->belongsTo(MasRequisitionType::class, 'requisition_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function details() 
    {
        return $this->hasMany(RequisitionApplication::class, 'requisition_id');
    }

    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if($request->req_type){
            $query->where('requisition_type_id', $request->req_type);
        }

        if ($onesOwnRecord) {
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
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }
    }
}
