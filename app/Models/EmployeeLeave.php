<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory, CreatedByTrait;

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(MasLeaveType::class, 'mas_leave_type_id');
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('mas_leave_type_id') && $request->query('mas_leave_type_id') != '') {
            $query->where('mas_leave_type_id', $request->query('mas_leave_type_id'));
        }
        if ($request->has('department') && $request->query('department') != '') {
            $query->whereHas('employee.empJob.department', function ($q) use ($request) {
                $q->where('id', $request->query('department'));
            });
        }
        if ($request->has('section') && $request->query('section') != '') {
            $query->whereHas('employee.empJob.section', function ($q) use ($request) {
                $q->where('id', $request->query('section'));
            });
        }
        if ($request->get('year')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('year'));

            // Step 2: Convert each date to Y-m format using Carbon
            $startDate = Carbon::createFromFormat('Y-m', trim($dates[0]));

            // Extract year and month
            $year = $startDate->year;
            $month = $startDate->month;

            // Filter by year and month
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }
    }
}
