<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class AdvanceApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'advance_no',
        'date',
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
        'remark',
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


    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('advance_type') && $request->query('advance_type') != '') {
            $query->where('type_id', $request->query('advance_type'));
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
            $query->whereYear('date', $year)
            ->whereMonth('date', $month);
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
                if($advance->type_id == GADGET_EMI && $advance->status == 3) {
                    $payHeadId = \DB::table('mas_pay_heads')
                    ->join('mas_advance_types', 'mas_pay_heads.general_ledger_code', '=', 'mas_advance_types.code')
                    ->where('mas_advance_types.id', $advance->type_id)
                    ->value('mas_pay_heads.id');

                if ($payHeadId) {
                    $advance->insertInToLoanEmiDeductions($payHeadId);
                }
            }
        });
    }

    public function insertInToLoanEmiDeductions($payHeadId)
    {
        $startDate = Carbon::parse($this->deduction_from_period); // Ensure Carbon instance
        $endDate = $startDate->copy()->addMonths($this->no_of_emi)->subDay();

        \App\Models\LoanEMIDeduction::create([
            'mas_pay_head_id' => $payHeadId,
            'mas_employee_id' => $this->created_by,
            'start_date' => $this->deduction_from_period,
            'end_date' => $endDate,
            'amount' => $this->monthly_emi_amount,
            'loan_number' => $this->advance_no,
            'loan_type_id' => 4,
            'recurring' => 1,
            'recurring_months' => $this->no_of_emi,
            'remarks' => $this->remark ?? null,
            'is_paid_off' => 0,
            'created_at' => now(),
        ]);
    }
}
