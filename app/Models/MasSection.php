<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class MasSection extends Model
{
    use HasFactory, CreatedByTrait;
    
    public function employee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

    public function department(){
        return $this->belongsTo(MasDepartment::class, 'mas_department_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('section') && $request->query('section') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('section') . '%');
        }
        if ($request->has('department') && $request->query('department') != '')
        {
            $query->where('mas_department_id', $request->query('department'));
        }
    }
}
