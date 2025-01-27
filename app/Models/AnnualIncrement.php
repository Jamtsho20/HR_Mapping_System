<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualIncrement extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['for_month', 'status'];

    public function getStatusAttribute($value)
    {
        $statuses = [
            0 => 'New',
            1 => 'Approved',
            2 => 'Finalized',
        ];

        return ['key' => $value, 'label' => $statuses[$value] ?? 'Unknown'];
    }

    public function incrementDetails()
    {
        return $this->hasMany(AnnualIncrementDetail::class, 'annual_increment_id');
    }
}
