<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MasSite extends Model
{
    use HasFactory;

    protected $fillable = [ 'code', 'name', 'description', 'dzongkhag_id'];

    public function reqDetail()
    {
        return $this->hasOne(RequisitionDetail::class, 'site_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }

    public function supervisor(){
        return $this->belongsTo(User::class, 'site_supervisor');
    }


    public function scopeFilter($query, $request)
    {
        if ($request->has('mas_site') && $request->query('mas_site') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('mas_site'));
        }

        if ($request->has('dzongkhag') && $request->query('dzongkhag') != '') {
                $query->whereHas('dzongkhag', function ($q) use ($request) {
                    $q->where('dzongkhag', 'LIKE', '%' . $request->query('dzongkhag') . '%');
                });
            }


        if($request->has('status') && $request->query('status') != '') {
            $query->where('status', $request->status);
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
