<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;

    // protected $fillable = ['transfer_claim_no ', 'transfer_claim_id', 'current_location', 'new_location', 'distance_travelled', 'amount_claimed', 'attachment', 'status'];

    protected $guarded = [];

    protected $cast = ['attachment' => 'array'];

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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function transfer_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function type()
    {
        return $this->belongsTo(MasTransferClaim::class, 'type_id');
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('type_id') && $request->query('type_id') != '') {
            $query->where('type_id', $request->query('type_id'));
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

        if ($onesOwnRecord) {
            $query->where('created_by', auth()->user()->id);
        }
    }

    public function expense_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
