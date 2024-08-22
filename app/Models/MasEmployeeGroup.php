<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeGroup extends Model
{
    use HasFactory, CreatedByTrait;

    public function masEmpGroupMap()
    {
        return $this->hasMany(MasEmployeeGroupMap::class, 'mas_employee_group_id');
    }
    public function payGroupDetails()
    {
        return $this->hasMany(MasPayGroupDetail::class, 'employee_category', 'id');
    }
    public function scopeFilter($query, $request)
    {
        // Filter by name
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }

        // Filter by description
        if ($request->has('description') && $request->query('description') != '') {
            $query->where('description', 'LIKE', '%' . $request->query('description') . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->query('status') != '') {
            $status = $request->query('status') == 'active' ? 1 : 0;
            $query->where('status', $status);
        }

    }
}
