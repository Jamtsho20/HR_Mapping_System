<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveFixedAllowance extends Model
{
    use HasFactory;

    public function employee() {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    public function payHead() {
        return $this->belongsTo(MasPayHead::class, 'pay_head_id', 'id');
    }

    public function scopeFilter($query, $request)
    {
        $keyword = trim($request->query('search'));

        if ($request->has('employee_name') && $request->query('employee_name') != '') {
            $query->whereHas('employee',  function($employeeQuery) use ($request, $keyword) {
                $employeeQuery->where('name', 'like', '%' . $keyword. '%')
                              ->orWhere('employee_id', 'like', '%' . $keyword. '%');
            });
        }
    }
}
