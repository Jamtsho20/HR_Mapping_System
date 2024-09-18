<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class PaySlip extends Model
{
    use HasFactory, CreatedByTrait;

    public function details()
    {
        return $this->hasMany(PaySlipDetail::class, 'pay_slip_id');
    }

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
