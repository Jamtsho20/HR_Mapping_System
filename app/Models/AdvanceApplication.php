<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AdvanceApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'transaction_no',
        'transaction_date',
        'type_id',
        'travel_authorization_id',
        'mas_employee_id',
        'mode_of_travel',
        'from_location',
        'to_location',
        'advance_settlement_date',
        'from_date',
        'to_date',
        'amount',
        'remarks',
        'attachment',
        'interest_rate',
        'total_amount',
        'no_of_emi',
        'monthly_emi_amount',
        'deduction_from_period',
        'item_type',
        'status',

    ];
    protected $cast = [
        'date' => 'date'
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function rejectRemarks()
    {
        return $this->hasOne(ApplicationHistory::class, 'application_id', 'id')
            ->where('application_type', self::class)
            ->select('remarks', 'application_id');
    }

    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function verified_by()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application')->where('status', '2');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function advance_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function advanceType()
    {
        return $this->belongsTo(MasAdvanceTypes::class, 'type_id');
    }

    public function type()
    {
        return $this->belongsTo(MasAdvanceTypes::class, 'type_id');
    }

    public function advanceDetails()
    {
        return $this->hasMany(AdvanceDetail::class, 'advance_application_id');
    }

    public function travelAuthorization()
    {
        return $this->belongsTo(TravelAuthorizationApplication::class, 'travel_authorization_id');
    }

    public function emiDeductions()
    {
        return $this->hasMany(LoanEMIDeduction::class, 'loan_number', 'transaction_no');
    }

    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('advance_type') && $request->query('advance_type') != '') {
            $query->where('type_id', $request->query('advance_type'));
        }

        if ($request->has('status') && $request->query('status') != '') {
            $query->where('status', $request->query('status'));
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
            $query->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month);
        }

        if ($request->get('date')) {
            // Step 1: Split the date range into two parts
            $dates = explode(' - ', $request->get('date'));

            // Step 2: Convert each date to Y-m-d format using Carbon
            $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->format('Y-m-d');

            // Step 3: Apply the date range filter
            if ($startDate === $endDate) {
                $query->whereDate('transaction_date', $startDate);
            } else {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
            }
        }
        if ($request->has('employee') && $request->get('employee')) {
            $query->where('created_by', $request->get('employee'));
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

        if ($request->has('name') && $request->get('name') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('name') . '%');
            });
        }
        if ($request->has('cid_no') && $request->query('cid_no') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('cid_no', $request->query('cid_no'));
            });
        }
    }

    public function setDeductionFromPeriodAttribute($value)
    {
        $this->attributes['deduction_from_period'] = Carbon::parse($value)->format('Y-m-01');
    }

    public function getStatusNameAttribute()
    {
        $statusNameMapping = config('global.application_status');
        return $statusNameMapping[$this->status] ?? config('global.null_value');
    }

    //insert to loan_e_m_i_deductions table as when advance for device emi gets approved
    protected static function boot()
    {
        parent::boot();
        static::updated(function ($advance) {
            if (($advance->type_id == GADGET_EMI || $advance->type_id == SIFA_LOAN) && $advance->status == 3) {
                $payHeadId = \DB::table('mas_pay_heads')
                    ->join('mas_advance_types', 'mas_pay_heads.general_ledger_code', '=', 'mas_advance_types.code')
                    ->where('mas_advance_types.id', $advance->type_id)
                    ->value('mas_pay_heads.id');

                if ($payHeadId) {
                    $advance->insertInToLoanEmiDeductions($payHeadId);
                }
            }
            // dd($advance);
            // if($advance->status == 4) {
            //     dd($request->all());
            //     $auditData = ApplicationAuditLog::where('application_id', $advance->id)->where('application_type', \App\Models\AdvanceApplication::class)->first();
            //     ApplicationAuditLog::create([
            //         'application_type' => $auditData->application_type,
            //         'application_id' => $auditData->application_id,
            //         'approval_option' => $auditData->approval_option ?? null,
            //         'hierarchy_id' => $auditData->hierarchy_id ?? null,
            //         'status' => $advance->status,
            //         'remarks' => $advance->remarks ?? null,
            //         'action_performed_by' => auth()->user()->id,
            //         'edited_by' => $auditData->edited_by ?? null,
            //         'sap_response' => $advance->sap_response ?? null,
            //     ]);
            // }
        });
    }

    public function insertInToLoanEmiDeductions($payHeadId)
    {
        $startDate = Carbon::parse($this->deduction_from_period);
        $endDate = $startDate->copy()->addMonths($this->no_of_emi)->subDay();

        // Map your advance type_id to loan_type_id in loan_emi_deductions table
        $loanTypeMap = [
            GADGET_EMI => 4,
            SIFA_LOAN => 11,
        ];

        \App\Models\LoanEMIDeduction::create([
            'mas_pay_head_id' => $payHeadId,
            'mas_employee_id' => $this->created_by,
            'start_date' => $this->deduction_from_period,
            'end_date' => $endDate,
            'amount' => $this->monthly_emi_amount,
            'loan_number' => $this->transaction_no,
            'loan_type_id' => $loanTypeMap[$this->type_id] ?? null, // dynamically assign
            'recurring' => 1,
            'recurring_months' => $this->no_of_emi,
            'remarks' => $this->remarks ?? null,
            'is_paid_off' => 0,
            'created_at' => now(),
        ]);
    }
}
