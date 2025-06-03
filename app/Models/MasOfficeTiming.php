<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasOfficeTiming extends Model
{
    use HasFactory;

    // public function scopeWinter($query)
    // {
    //     return $query->where('season', self::WINTER);
    // }

    // public function scopeSummer($query)
    // {
    //     return $query->where('season', self::SUMMER);
    // }

    public function getSeasonNameAttribute(){
        return config('global.seasons')[$this->season];
    }

    public function getStartMonthNameAttribute(){
        return config('global.months')[$this->start_month];
    }

    public function getEndMonthNameAttribute(){
        return config('global.months')[$this->start_month];
    }
}
