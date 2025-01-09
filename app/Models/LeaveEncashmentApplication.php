<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Traits\UpdateLeaveBalanceTrait;
use Carbon\Carbon;

class LeaveEncashmentApplication extends Model
{
    use HasFactory, UpdateLeaveBalanceTrait;

    protected $table = 'leave_encashment_applications';

    protected $fillable = [
        'mas_employee_id',
        'type_id',
        'leave_applied_for_encashment',
        'created_by',
        'updated_by',
        'status',
        'tax_amount',
        'post_to_sap',
        'amount',
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function type()
    {
        return $this->belongsTo(LeaveEncashmentType::class, 'type_id');
    }
    public function employeeLeave()
    {
        return $this->hasOne(EmployeeLeave::class, 'mas_leave_type_id', 'type_id');
    }



    // public function employee()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    protected static function booted()
    {
        static::created(function ($leaveEncashment) {
            $leaveEncashment->updateLeaveBalance(null, $leaveEncashment);
        });

        static::updated(function ($leaveEncashment) {
            if ($leaveEncashment->isDirty('status') && $leaveEncashment->status == -1) {
                $leaveEncashment->updateLeaveBalance(null, $leaveEncashment);
            }
        });
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function leave_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->get('year')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('year'));

            // Step 2: Convert each date to Y-m format using Carbon
            $startDate = Carbon::createFromFormat('Y-m', trim($dates[0]));

            // Extract year and month
            $year = $startDate->year;
            $month = $startDate->month;

            // Filter by year and month
            $query->whereYear('leave_encashment_applications.created_at', $year)
                ->whereMonth('leave_encashment_applications.created_at', $month);
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

        // Add more filters here if needed
        return $query;
    }
}
