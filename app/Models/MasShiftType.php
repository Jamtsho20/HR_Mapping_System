<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasShiftType extends Model
{
    use HasFactory,CreatedByTrait;

    protected $fillable = [
        'name', 'start_time', 'end_time'
    ];

    public function departmentShifts(){
        return $this->hasMany(DepartmentWiseShift::class, 'type_id');
    }
      public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
