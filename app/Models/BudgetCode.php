<?php

namespace App\Models;

use App\Models\BudgetTypes;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCode extends Model
{
    use HasFactory,CreatedByTrait;
    protected $fillable = [
        'code',
        'particular',
        'budget_type_id'
    ];
    public function budgetType()
    {
        return $this->belongsTo(BudgetTypes::class, 'budget_type_id');
    }
}
