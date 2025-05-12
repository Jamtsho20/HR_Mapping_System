<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AssetTransferApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'transaction_no',
        'type_id',
        'transaction_date',
        'reason_of_transfer',
        'from_employee_id',
        'to_employee_id',
        'from_site_id',
        'to_site_id',
        'attachement',
        'doc_no',
        'status',
    ];
    public function transferType()
    {
        return $this->belongsTo(MasTransferType::class, 'type_id');
    }


    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function details ()
    {
        return $this->hasMany(AssetTransferDetail::class, 'asset_transfer_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function fromEmployee()
    {
        return $this->belongsTo(User::class, 'from_employee_id');
    }

    public function toEmployee()
    {
        return $this->belongsTo(User::class, 'to_employee_id');
    }

    public function fromSite()
    {
        return $this->belongsTo(MasSite::class, 'from_site_id');
    }

    public function toSite()
    {
        return $this->belongsTo(MasSite::class, 'to_site_id');
    }

    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function type()
    {
        return $this->belongsTo(MasTransferType::class, 'type_id');
    }
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if($request->type_id){
            $query->where('type_id', $request->type_id);
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

    protected static function booted()
    {
        static::updated(function ($transfer) {
            if ($transfer->isDirty('status') && $transfer->status == -1) {
                $transfer->load('details.receivedSerial');
                foreach ($transfer->details as $detail) {
                    if ($detail->receivedSerial) {
                        $detail->receivedSerial->update(['is_transfered' => 0]);
                        $detail->receivedSerial->update(['is_transfered_to' => null]);
                    }
                }
            }
        });
    }
}
