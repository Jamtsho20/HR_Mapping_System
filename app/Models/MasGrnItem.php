<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGrnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_no',
        'last_synced_at',
        'status'
    ];

    public function detail()
    {
        return $this->hasMany(MasGrnItemDetail::class, 'grn_id');
    }

    public function requisitionDetails()
    {
        return $this->hasMany(RequisitionDetail::class, 'grn_item_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('grn_no') && $request->query('grn_no') != '') {
            $query->where('grn_no', $request->query('grn_no'));
        }
    }
}
