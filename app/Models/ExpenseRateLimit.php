<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseRateLimit extends Model
{
    protected $fillable = [
        'expense_rate_definition_id',
        'mas_grade_step_id',
        'mas_region_id',
        'limit_amount',
        'start_date',
        'end_date',       
        'status',
    ];
    use HasFactory;
  
}
