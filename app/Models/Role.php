<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class Role extends Model
{
    use HasFactory, CreatedByTrait;

    protected $guarded = ['id'];

    // Relations
    public function users()
    {
        return $this->belongsToMany(User::class, 'mas_employee_roles', 'role_id', 'mas_employee_id')
                    ->withPivot('created_by', 'updated_by')
                    ->withTimestamps();
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
