<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function employee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function leaveType(){
        return $this->belongsTo(MasLeaveType::class, 'mas_leave_type_id');
    }

    public function scopeFilter($query, $request){
        if ($request->has('leave_type') && $request->query('leave_type') != '') {
            $query->where('mas_leave_type_id', $request->query('leave_type'));
        }
        $query->where('mas_employee_id', auth()->user()->id);
    }

    public function getStatusNameAttribute() {
        $statusNameMapping = config('global.application_status');
        return $statusNameMapping[$this->status] ?? config('global.null_value');
    }
}
