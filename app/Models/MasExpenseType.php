<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasExpenseType extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = ['name', 'mas_expense_type_id'];

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    // In MasExpenseType model

    public function parent()
    {
        return $this->belongsTo(MasExpenseType::class, 'mas_expense_type_id');
    }

    public function children()
    {
        return $this->hasMany(MasExpenseType::class, 'mas_expense_type_id');
    }

    //accessors & mutators
    public function setExpenseTypeAttribute($value)
    {
        $this->attributes['expense_type'] = ucwords($value);
    }
    //scopes & filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('expense_type') && $request->query('expense_type') != '') {
            $query->where('expense_type', 'LIKE', '%' . $request->query('expense_type') . '%');
        }
    }

}
