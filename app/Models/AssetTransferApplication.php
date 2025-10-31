<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\AssetTransferDetail;
use App\Models\MasTransferType;

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

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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

       if($request->from_date && $request->to_date){
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }elseif ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if($request->transfer_no){
            $query->where('transaction_no', $request->transfer_no);
        }

        if($request->status){
            $query->where('status', $request->status);
        }

    }

    protected static function booted()
    {
        static::updated(function ($transfer) {

            // Reset transferred flags if status is -1
            if ($transfer->isDirty('status') && $transfer->status == -1) {
                $transfer->load('details.asset');
                foreach ($transfer->details as $detail) {
                    if ($detail->asset) {
                        $detail->asset->update([
                            'is_transfered' => 0,
                        ]);
                    }
                }
            }

            // Update MasAssets if received_acknowledged is now true
            if ($transfer->isDirty('received_acknowledged') && $transfer->received_acknowledged == 1) {
                $transfer->load('details');
                foreach ($transfer->details as $detail) {
                    if (!$detail->received_serial_id) {
                        continue;
                    }

                    $masAsset = \App\Models\MasAssets::where('id', $detail->mas_asset_id)->first();

                    if ($masAsset) {
                       $updateData = [
                            'current_employee_id' => $transfer->to_employee_id,
                            'asset_transfer_detail_id' => $detail->id,
                            'status' => 2,
                            'updated_by' => auth()->user()->id,
                            'updated_at' => now(),
                        ];

                        // Only update current_site_id if it's provided
                        if (!is_null($transfer->to_site_id)) {
                            $updateData['current_site_id'] = $transfer->to_site_id;
                        }

                        $masAsset->update($updateData);

                    }
                }
            }
        });
    }

}
