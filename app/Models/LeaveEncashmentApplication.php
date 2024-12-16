<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;
use App\Traits\UpdateLeaveBalanceTrait;

class LeaveEncashmentApplication extends Model
{
    use HasFactory, UpdateLeaveBalanceTrait;

    protected $table = 'leave_encashment_applications';

    protected $fillable = [
        'mas_employee_id',
        'type_id',
        'leave_applied_for_encashment',
        'created_by',
        'updated_by',
        'status',
        'post_to_sap',
        'amount',
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function type()
    {
        return $this->belongsTo(LeaveEncashmentType::class, 'type_id');
    }

    // public function employee()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    protected static function booted()
    {
        static::updated(function ($leaveEncashment) {
            if ($leaveEncashment->isDirty('status') && $leaveEncashment->status == 3) {
                $leaveEncashment->updateLeaveBalance(null, $leaveEncashment);
            }
        });
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {

    }


}
