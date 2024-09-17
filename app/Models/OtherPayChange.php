<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPayChange extends Model
{
    use HasFactory, CreatedByTrait;

    /**
     * Set the for_month attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setForMonthAttribute($value)
    {
        $this->attributes['for_month'] = Carbon::parse($value)->format('Y-m-01');
    }

    public function details()
    {
        return $this->hasMany(OtherPayChangeDetail::class, 'other_pay_change_id');
    }

    /**
     * Get the for_month attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function getStatusAttribute($value)
    {
        $statuses = [
            0 => 'Cancelled',
            1 => 'New',
            2 => 'Processed',
            3 => 'Verified',
            4 => 'Approved',
        ];

        return ['key' => $value, 'label' => $statuses[$value] ?? 'Unknown'];
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('for_month') && $request->query('for_month') != '') {
            $query->where('for_month', $request->query('for_month'));
        }
    }
}
