<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $cast = [
        'attachment' => 'array'
    ];

    public function commisionType ()
    {
        return $this->belongsTo(MasCommissionTypes::class, 'commission_type_id');
    }
}
