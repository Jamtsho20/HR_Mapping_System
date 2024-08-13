<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeeJob extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id', 'mas_grade_id', 'mas_grade_step_id'];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
