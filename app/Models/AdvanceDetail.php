<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_application_id', 'budget_code_id', 'from_date', 'to_date', 'dzongkhag_id', 'site_location', 'amount_required', 'purpose'
    ];

    public function advanceApplication()
    {
        return $this->belongsTo(AdvanceApplication::class, 'advance_apllication_id');
    }

    public function budgetCode()
    {
        return $this->belongsTo(BudgetCode::class, 'budget_code_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }
}
