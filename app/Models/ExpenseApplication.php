<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseApplication extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = [
        // 'mas_employee_id',
        'mas_expense_type_id',
        'date',
        'expense_amount',
        'description',
        'file',
        'travel_type',
        'travel_mode',
        'travel_from_date',
        'travel_to_date',
        'travel_from',
        'travel_to',
        'status'
    ];
    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    
    public function expenseType()
    {
        return $this->belongsTo(MasExpenseType::class, 'mas_expense_type_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('mas_expense_type_id') && $request->query('mas_expense_type_id') != '') {
            $query->where('mas_expense_type_id', $request->query('mas_expense_type_id'));
        }
       
    }
}
