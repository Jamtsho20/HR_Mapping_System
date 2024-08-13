<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'employment_contract', 'non_disclosure_aggrement', 'job_responsibilities', 'other']; 
    protected $cast = [
        'other' => 'array'
    ];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
