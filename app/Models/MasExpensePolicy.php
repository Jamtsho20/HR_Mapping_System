<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasExpensePolicy extends Model
{
    protected $fillable = ['type_id', 'name', 'description', 'start_date', 'end_date', 'status']; 

    use HasFactory, CreatedByTrait;
    //realtions
    public function expenseType()
    {
        return $this->belongsTo(MasExpenseType::class, 'type_id');
    }
    public function rateDefinition()
    {
        return $this->hasOne(ExpenseRateDefinition::class, 'mas_expense_policy_id');
    }
    public function policyEnforcement()
    {
        return $this->hasOne(ExpensePolicyEnforcement::class, 'mas_expense_policy_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('code') && $request->query('code') != '') {
            $query->where('code', 'LIKE', '%' . $request->query('code') . '%');
        }
    }
}
