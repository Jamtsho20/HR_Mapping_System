<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeExperience extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'organization', 'place' , 'designation', 'start_date', 'end_date', 'description'];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
