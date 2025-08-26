<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class FieldEmployee extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'mas_employee_id',
    ];
   public function masEmployee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function scopeFilter($query, $request)
    {
        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('mas_employee_id', 'LIKE', '%' . $request->query('employee') . '%');
        }
    }
}
