<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory, CreatedByTrait;

    public function employee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function leaveType(){
        return $this->belongsTo(MasLeaveType::class, 'mas_leave_type_id');
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('mas_leave_type_id') && $request->query('mas_leave_type_id') != '')
        {
            $query->where('mas_leave_type_id', $request->query('mas_leave_type_id'));
        }
    }
}
