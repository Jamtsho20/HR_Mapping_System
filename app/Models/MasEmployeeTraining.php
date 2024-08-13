<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeTraining extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'title', 'start_date', 'end_date', 'duration', 'location', 'description', 'certificate'];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
