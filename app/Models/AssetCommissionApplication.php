<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;


class AssetCommissionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'id',
        'type_id',
        'transaction_no',
        'transaction_date',
        'requisition_detail_id',
        'file',
        'doc_no',
        'status'
    ];

    protected $cast = [
        'file' => 'array'
    ];

    public function commisionType()
    {
        return $this->belongsTo(MasCommissionTypes::class, 'commission_type_id');
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

    // public function goodsReceivedDetail()
    // {
    //     return $this->belongsTo(GoodsReceivedDetail::class, 'goods_received_detail_id');
    // }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($onesOwnRecord) {
            $query->where('created_by', auth()->user()->id);
        }
    }
}
