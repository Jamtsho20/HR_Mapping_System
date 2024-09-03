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
}
