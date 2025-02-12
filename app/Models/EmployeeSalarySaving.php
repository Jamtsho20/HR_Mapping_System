<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalarySaving extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('employee_id', $request->query('employee'));
        }

    }
}
