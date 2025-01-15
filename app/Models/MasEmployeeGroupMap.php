<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeGroupMap extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = ['mas_employee_id', 'mas_employee_group_id'];

    public function employee(){
        return $this->belongsTo(User::class,'mas_employee_id');
    }
    public function masEmpGroup(){
        return $this->belongsTo(MasEmployeeGroup::class,'mas_employee_group_id');
    }
}
