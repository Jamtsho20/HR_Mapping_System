<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveTravelConcession extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['for_month', 'status'];

    public function getStatusAttribute($value)
    {
        $statuses = [
            0 => 'New',
            1 => 'Processed',
            2 => 'Finalized',
        ];

        return ['key' => $value, 'label' => $statuses[$value] ?? 'Unknown'];
    }

    public function ltcDetails()
    {
        return $this->hasMany(LeaveTravelConcessionDetail::class, 'ltc_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->get('year')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('year'));

            // Step 2: Convert each date to Y-m format using Carbon
            $startDate = Carbon::createFromFormat('Y-m', trim($dates[0]));

            // Extract year and month
            $year = $startDate->year;
            $month = $startDate->month;

            // Filter by year and month
            $query->whereYear('for_month', $year)
                ->whereMonth('for_month', $month);
        }
   

        // Add more filters here if needed
        return $query;
    }
}
