<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function scopeFilter($query, $request){
        if ($request->has('leave_type') && $request->query('leave_type') != '') {
            $query->where('mas_leave_type_id', $request->query('leave_type'));
        }
        $query->where('mas_employee_id', auth()->user()->id);
    }
}
