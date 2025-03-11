<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RequisitionDetail;

class RequisitionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'type_id',
        'transaction_no',
        'tansaction_date',
        'need_by_date',
        'requested_by',
        'status',
        'doc_no',
        'good_issue_doc_no',
        'is_received',
        'received_at',
        'received_by'

    ];

    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(RequisitionDetail::class, 'requisition_id');
    }

    public function grnMapping()
        {
            return $this->hasMany(GrnItemMapping::class, 'requisition_id');
        }


    public function goodsReceivedByUser()
    {
        return $this->hasOne(MasGoodsReceivedByUser::class, 'requisition_id');
    }

    public function type()
    {
        return $this->belongsTo(MasRequisitionType::class, 'type_id');
    }

    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        // if($request->req_type){
        //     $query->where('type_id', $request->req_type);
        // }

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
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }
    }

    protected static function booted()
    {
        static::updated(function ($requisition) {
            if ($requisition->isDirty('status') && $requisition->status == -1) {
                $requisition->restoreStock();
            }
        });
    }

    public function restoreStock()
{
    // Ensure details relationship is loaded
    $this->loadMissing('details');

    foreach ($this->details as $detail) {

        if (!$detail->grn_item_mapping_id) {
            \Log::warning("Invalid GRN data for detail ID: {$detail->id}", ['grn_no' => $detail->grn_no]);
            continue; // Skip if data is invalid
        }

        // Find the GRN item mapping entry
        $grnItem = GrnItemMapping::find($detail->grn_item_mapping_id);

        if (!$grnItem) {
            \Log::warning("GRN Item Mapping not found for ID: {$detail->grn_item_mapping_id}");
            continue;
        }

        // Restore the stock
        $grnItem->increment('current_stock', $detail->quantity_required);
        $grnItem->decrement('changed_quantity', $detail->quantity_required);
    }
}


}
