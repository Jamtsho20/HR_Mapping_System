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

    public function type()
    {
        return $this->belongsTo(MasRequisitionType::class, 'type_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if($request->req_type){
            $query->where('type_id', $request->req_type);
        }

        if($request->from_date && $request->to_date){
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }elseif ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if($request->req_no){
            $query->where('transaction_no', $request->req_no);
        }

        if($request->status){
            $query->where('status', $request->status);
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
        // Only restore stock if requisition type requires GRN handling
        if ($this->type_id != 1) {
            return;
        }

        $this->loadMissing('details');

        foreach ($this->details as $detail) {
            if (!$detail->grn_item_id) {
                \Log::warning("Invalid GRN item ID for detail ID: {$detail->id}", ['grn_item_id' => $detail->grn_item_id]);
                continue;
            }

            $grnItem = MasGrnItemDetail::find($detail->grn_item_id);

            if (!$grnItem) {
                \Log::warning("GRN Item not found for ID: {$detail->grn_item_id}");
                continue;
            }

            // Restore the stock
            $grnItem->increment('quantity', $detail->requested_quantity);
        }
    }


}
