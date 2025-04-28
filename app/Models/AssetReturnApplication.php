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
    }

}
