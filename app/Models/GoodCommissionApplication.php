<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;


class GoodCommissionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $cast = [
        'attachment' => 'array'
    ];

    public function commisionType ()
    {
        return $this->belongsTo(MasCommissionTypes::class, 'commission_type_id');
    }
    public function detail ()
    {
        return $this->hasMany(GoodCommissionApplicationDetail::class, 'good_commission_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
