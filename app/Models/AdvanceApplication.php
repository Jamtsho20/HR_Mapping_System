<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceApplication extends Model
{
    use HasFactory,CreatedByTrait;
    protected $fillable = [
        'advance_no',
        'date',
        'advance_type',
        'mas_employee_id',
        'mode_of_travel',
        'from_location',
        'to_location',
        'from_date',
        'to_date',
        'amount',
        'purpose',
        'attachment',
        'interest_rate',
        'total_amount', 
        'no_of_emi',
        'monthly_emi_amount',
        'deduction_from_period',
        'item_type',
        
    ];
    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    public function advanceType()
    {
        return $this->belongsTo(MasAdvanceTypes::class, 'advance_type', 'id');
    }
}
