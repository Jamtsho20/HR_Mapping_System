<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetCommissionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'id',
        'type_id',
        'transaction_no',
        'transaction_date',
        'requisition_id',
        'file',
        'doc_no',
        'status'
    ];

    // protected $cast = [
    //     'file' => 'array'
    // ];

    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function type()
    {
        return $this->belongsTo(MasCommissionTypes::class, 'type_id');
    }
    public function details()
    {
        return $this->hasMany(AssetCommissionDetail::class, 'commission_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function empJob()
    {
        return $this->hasOne(MasEmployeeJob::class, 'mas_employee_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function requisition()
    {
        return $this->belongsTo(RequisitionApplication::class, 'requisition_id');
    }

    public function requisitionDetail()
    {
        return $this->belongsTo(RequisitionDetail::class, 'requisition_detail_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($onesOwnRecord) {
            $query->where('created_by', auth()->user()->id);
        }

        if($request->from_date && $request->to_date){
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }elseif ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if($request->comm_no){
            $query->where('transaction_no', $request->comm_no);
        }

        if($request->status){
            $query->where('status', $request->status);
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

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($commissionApplication) {
            // Check if the status is changed to -1
            if ($commissionApplication->isDirty('status') && $commissionApplication->status == -1) {
                // Get all related commission details
                $details = $commissionApplication->details;

                // Extract received_serial_id values
                $receivedSerialIds = $details->pluck('received_serial_id')->toArray();

                if (!empty($receivedSerialIds)) {
                    // Update received_serials table to set is_commissioned = 0
                    DB::table('received_serials')
                        ->whereIn('id', $receivedSerialIds)
                        ->update([
                            'is_commissioned' => 0,
                            'updated_at' => now(),
                        ]);
                }
            }
              if ($commissionApplication->wasChanged('status') && $commissionApplication->status == 3) {
                foreach ($commissionApplication->details as $detail) {
                    $detail->loadMissing('receivedSerial.requisitionDetail.grnItemDetail.item');

                    $serialId = $detail->received_serial_id;

                    if (!$serialId || MasAssets::where('received_serial_id', $serialId)->exists()) {
                        continue;
                    }

                    \App\Models\MasAssets::create([
                        'serial_number' => $detail->receivedSerial->asset_serial_no,
                        'current_employee_id' => $commissionApplication->created_by,
                        'item_id' => $detail->receivedSerial->requisitionDetail->item_id
                            ?? optional($detail->receivedSerial->requisitionDetail->grnItemDetail->item)->id,
                        'current_site_id' => $detail->site_id,
                        'received_serial_id' => $detail->received_serial_id,
                        'commission_detail_id' => $detail->id,
                        'initial_owner_id' => $commissionApplication->created_by,

                    ]);
                }
            }
        });
    }

}
