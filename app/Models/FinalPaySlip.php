<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalPaySlip extends Model
{
    use HasFactory;

    protected $fillable = ['mas_employee_id', 'for_month', 'details'];
    
    protected $casts = [
        'details' => 'array', // Cast details as an array
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

}
