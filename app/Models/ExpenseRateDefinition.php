<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseRateDefinition extends Model
{
    use HasFactory;
    protected $fillable = ['mas_expense_policy_id', 'attachment_required', 'travel_type', 'rate_currency', 'currency', 'rate_limit'];

    public function expenseRateLimits()
    {
        return $this->hasMany(ExpenseRateLimit::class, 'expense_rate_definition_id');
    }
}
