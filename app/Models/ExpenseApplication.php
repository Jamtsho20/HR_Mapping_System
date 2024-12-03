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
        'expense_no',
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

    public function travelType()
    {
        return $this->belongsTo(MasTravelType::class, 'travel_type');
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('expense_type') && $request->query('expense_type') != '') {
            $query->where('mas_expense_type_id', $request->query('expense_type'));
        }

        if($onesOwnRecord){
            $query->where('created_by', auth()->user()->id);
        }
    }
    public function expense_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
