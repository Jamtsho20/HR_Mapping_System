<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsaClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $guarded = [];

    protected $fillable = ['dsa_claim_no', 'advance_application_id', 'travel_authorization_id', 'type_id', 'attachment', 'amount', 'net_payable_amount', 'balance_amount', 'status', 'advance_amount', 'total_number_of_days'];

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

    public function dsaClaimDetails() {
        return $this->hasMany(DsaClaimDetail::class, 'dsa_claim_id');
    }

    public function dsaClaimMappings() {
        return $this->hasMany(DsaClaimMappings::class, 'dsa_claim_id');
    }
    public function dsaadvance()
    {
        return $this->belongsTo(AdvanceApplication::class, 'advance_application_id');
    }

    public function travel()
    {
        return $this->belongsTo(TravelAuthorizationApplication::class, 'travel_authorization_id');
    }

    public function type()
    {
        return $this->belongsTo(DsaClaimType::class, 'type_id');
    }


    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('expense_type') && $request->query('expense_type') != '') {

            $query->where('type_id', $request->query('expense_type'));
        }
        if ($request->has('status') && $request->query('status') !== '') {
            $query->where('status', $request->query('status'));
        }
        if ($request->has('employee') && $request->query('employee') !== '') {
            $query->where('created_by', $request->query('employee'));
        }
        if ($request->has('manager') && $request->query('manager') !== '') {
            $query->where('updated_by', $request->query('manager'));
        }
        if ($request->has('sap_trans_no') && $request->query('sap_trans_no') !== '') {
            $sapTransNo = $request->query('sap_trans_no');
        
            $query->whereHas('audit_logs', function ($q) use ($sapTransNo) {
                $q->where('status', 3)->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(sap_response, '$.data.JdtNum')) = ?", [$sapTransNo]);
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
