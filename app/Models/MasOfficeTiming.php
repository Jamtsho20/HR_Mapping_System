<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasOfficeTiming extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['season', 'start_month', 'end_month', 'start_time', 'lunch_time_from', 'lunch_time_to', 'end_time'];

    public function scopeFilter($query, $request)
    {
        if ($request->has('season') && $request->query('season') != '') {
            $query->where('season', $request->query('season'));
        }

        return $query;
    }

    public function getSeasonNameAttribute(){
        return config('global.seasons')[$this->season];
    }

    public function getStartMonthNameAttribute(){
        return config('global.months')[$this->start_month];
    }

    public function getEndMonthNameAttribute(){
        return config('global.months')[$this->end_month];
    }
}
