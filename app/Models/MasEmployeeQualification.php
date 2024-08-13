<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeQualification extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'mas_qualification_id', 'school', 'subject', 'completion_year', 'aggregate_score'];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
