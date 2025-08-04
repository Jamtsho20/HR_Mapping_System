<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetirementBenefit extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'mas_employee_id',
        'benefit_type_id',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function details()
    {
        return $this->hasMany(RetirementBenefitDetail::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function type()
    {
        return $this->belongsTo(MasRetirementBenefitTypes::class, 'benefit_type_id');
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
        if ($request->has('name') && $request->get('name') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('name') . '%');
            });
        }
    }
}
