<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensePolicyEnforcement extends Model
{
    use HasFactory;
    protected $fillable = ['mas_expense_policy_id', 'prevent_report_submission', 'display_warning_to_user']; 


}
