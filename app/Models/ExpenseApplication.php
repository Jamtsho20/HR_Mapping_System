<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseApplication extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = [
        // 'mas_employee_id',
        'expense_no',
        'type_id',
        'mas_vehicle_id',
        'date',
        'amount',
        'description',
        'file',
        'travel_type',
        'travel_mode',
        'travel_from_date',
        'travel_to_date',
        'travel_from',
        'travel_to',
        'status'
    ];
    protected $cast = [
        'date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

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
        return $this->belongsTo(MasExpenseType::class, 'type_id');
    }

    public function travelType()
    {
        return $this->belongsTo(MasTravelType::class, 'travel_type');
    }

    public function details() {
        return $this->hasMany(ExpenseFuelClaimDetail::class, 'expense_id');
    }

    public function vehicle() {
        return $this->belongsTo(MasVehicle::class, 'mas_vehicle_id');
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('expense_type') && $request->query('expense_type') != '') {
            $query->where('type_id', $request->query('expense_type'));
        }
        if ($request->has('employee') && $request->query('employee') !== '') {
            $query->where('created_by', $request->query('employee'));
        }
        if ($request->has('manager') && $request->query('manager') !== '') {
            $query->where('updated_by', $request->query('manager'));
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
        if ($request->has('region') && $request->query('region') != '') {
            $query->whereHas('employee.region', function ($q) use ($request) {
                $q->where('id', $request->query('region'));
            });
        }
        if ($request->has('office') && $request->query('office') != '') {
            $query->whereHas('employee.empJob.office', function ($q) use ($request) {
                $q->where('id', $request->query('office'));
            });
        }

        if ($request->has('name') && $request->get('name') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('name') . '%');
            });
        }

        if($onesOwnRecord){
            $query->where('created_by', auth()->user()->id);
        }
    }
    public function expense_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
