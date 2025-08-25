<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReturnApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['type_id','transaction_no','transaction_date','attachment','doc_no','status'];

    public function type()
    {
        return $this->belongsTo(MasReturnType::class, 'type_id');
    }
    public function details()
    {
        return $this->hasMany(AssetReturnDetail::class, 'asset_return_id');
    }

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
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('type_id') && $request->query('type_id') != '') {

            $query->where('type_id', $request->query('type_id'));
        }

         if($request->from_date && $request->to_date){
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }elseif ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if($request->return_no){
            $query->where('transaction_no', $request->return_no);
        }

        if($request->status){
            $query->where('status', $request->status);
        }
    }

    public static function booted(){
            static::updated(function ($assetReturn) {
                if ($assetReturn->isDirty('received_acknowledged') && $assetReturn->received_acknowledged == 1) {
                    foreach ($assetReturn->details as $detail) {
                        $masAsset = \App\Models\MasAssets::where('received_serial_id', $detail->received_serial_id)->first();

                        if ($masAsset) {
                            $masAsset->update([
                                'return_detail_id' => $detail->id,
                                'status' => 3
                            ]);
                        }
                    }
                }
            });
    }

}
