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

    public function users()
    {
        return $this->belongsToMany(User::class, 'mas_employee_group_maps', 'mas_employee_group_id', 'mas_employee_id')
                    ->withPivot('created_by', 'updated_by')
                    ->withTimestamps();
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
    public function employees()
    {
        return $this->hasManyThrough(
            User::class, // The related model
            MasEmployeeGroupMap::class, // The intermediate model
            'mas_employee_group_id', // Foreign key on MasEmployeeGroupMap
            'id', // Foreign key on User (mas_employees)
            'id', // Local key on MasEmployeeGroup
            'mas_employee_id' // Local key on MasEmployeeGroupMap
        );
    }
    
    
}
