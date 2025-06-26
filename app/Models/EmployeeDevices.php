<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDevices extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'device_id', 'device_name'];
}
