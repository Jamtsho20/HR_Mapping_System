<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlipDetailView extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function scopeFilter($query, $request)
    {
        $keyword = trim($request->query('search'));
        if ($request->has('search') && $request->query('search') != '') {
            $query->whereHas('employee', function($employeeQuery) use ($request, $keyword) {
                $employeeQuery->where('name', 'like', '%' . $keyword. '%')
                              ->orWhere('employee_id', 'like', '%' . $keyword. '%');
            });
        }
    }
}
