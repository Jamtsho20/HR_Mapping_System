<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveTravelConcessionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['ltc_id', 'mas_employee_id', 'amount'];

    public function ltc()
    {
        return $this->belongsTo(LeaveTravelConcession::class, 'ltc_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
}
