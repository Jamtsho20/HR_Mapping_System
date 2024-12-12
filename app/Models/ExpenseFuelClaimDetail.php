<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseFuelClaimDetail extends Model
{
    use HasFactory;

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expense_id',
        'date',
        'initial_reading',
        'final_reading',
        'quantity',
        'mileage',
        'rate',
        'amount',
    ];
    
    public function expenseApplication() {
        return $this->belongsTo(ExpenseApplication::class, 'expense_id');
    }
}
