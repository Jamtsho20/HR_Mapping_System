<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'mas_employee_id', 'department_shift_id', 'off_days'
    ];

    protected $cast = [
        'off_days' => 'array'
    ];

    public function masEmployee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
