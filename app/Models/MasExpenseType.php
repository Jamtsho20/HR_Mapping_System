<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasExpenseType extends Model
{
    use HasFactory, CreatedByTrait;

      //accessors & mutators
      public function setExpenseTypeAttribute($value)
      {
          $this->attributes['expense_type'] = ucwords($value);
      }
       //scopes & filters
       public function scopeFilter($query, $request)
       {
           if ($request->has('expense_type') && $request->query('expense_type') != '') {
               $query->where('expense_type', 'LIKE', '%' .$request->query('expense_type') . '%');
           }
       }
}
