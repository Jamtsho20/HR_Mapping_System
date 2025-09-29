<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingBudgetAllocation extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'training_budget_allocations';

    protected $fillable = [
        'training_list_id',
        'training_expense_type_id',
        'amount_allocated',
        'by_company',
        'by_sponsor'
    ];


    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
