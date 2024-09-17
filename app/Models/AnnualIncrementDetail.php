<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualIncrementDetail extends Model
{
    use HasFactory;

    protected $fillable = ['annual_increment_id', 'mas_employee_id', 'amount', 'status'];

    public function annualIncrement()
    {
        return $this->belongsTo(AnnualIncrement::class, 'annual_increment_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
