<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use App\Traits\UpdateLeaveBalanceTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory, CreatedByTrait, UpdateLeaveBalanceTrait;
    protected $fillable = [
        'type_id',
        'from_day',
        'to_day',
        'from_date',
        'to_date',
        'no_of_days',
        'remarks',
        'attachment',
        'status'
    ];

    protected $cast = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leave_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function leaveType()
    {
        return $this->belongsTo(MasLeaveType::class, 'type_id');
    }
    public function type()
    {
        return $this->belongsTo(MasLeaveType::class, 'type_id');
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        //specify table name final_pay_slip for loan report where tables are joined
        if ($request->has('employee_id') && $request->get('employee_id')) {
            $query->where('created_by', $request->get('employee_id'));
        }
        if ($request->has('leave_type') && $request->query('leave_type') != '') {
            $query->where('type_id', $request->query('leave_type'));
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


        if ($onesOwnRecord) {
            $query->where('created_by', auth()->user()->id);
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

    public function getStatusNameAttribute()
    {
        $statusNameMapping = config('global.application_status');
        return $statusNameMapping[$this->status] ?? config('global.null_value');
    }

    protected static function booted()
    {
        static::created(function ($leaveApplication) {
            $leaveApplication->updateLeaveBalance($leaveApplication);
        });

        static::updated(function ($leaveApplication) {
            if ($leaveApplication->isDirty('status') && $leaveApplication->status == -1) {
                $leaveApplication->updateLeaveBalance($leaveApplication);
            }
        });
    }
}
