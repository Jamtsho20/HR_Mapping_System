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
        if ($request->filled('employee_id')) {
            $query->whereHas('employee', function($employeeQuery) use ($request) {
                $employeeQuery->where('id', $request->query('employee_id'));
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($employeeQuery) use ($request) {
                $employeeQuery->whereHas('empJob', function ($jobQuery) use ($request) {
                    $jobQuery->where('mas_department_id', $request->query('department'));
                });
            });
        }

        if ($request->filled('employment_type')) {
            $query->whereHas('employee', function($employeeQuery) use ($request) {
                $employeeQuery->whereHas('empJob', function ($jobQuery) use ($request) {
                    $jobQuery->where('mas_employment_type_id', $request->query('employment_type'));
                });
            });
        }
    }
}
