<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeGroup extends Model
{
    use HasFactory;

    public function masEmpGroupMap(){
        return $this->hasMany(MasEmployeeGroupMap::class, 'mas_employee_group_id');
    }
}
