<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MasGrnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_no',
        'last_synced_at',
        'status'
    ];

    public function detail()
    {
        return $this->hasMany(MasGrnItemDetail::class, 'grn_id');
    }

    public function requisitionDetails()
    {
        return $this->hasMany(RequisitionDetail::class, 'grn_item_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('grn_no') && $request->query('grn_no') != '') {
            $query->where('grn_no', 'LIKE', '%' .$request->query('grn_no'));
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
