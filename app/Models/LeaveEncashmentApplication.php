<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class LeaveEncashmentApplication extends Model
{
    use HasFactory; 

    protected $table = 'leave_encashment_applications';

    protected $fillable = [
        'mas_employee_id',
        'leave_applied_for_encashment',
        'created_by',
        'updated_by',
        'status',
        'encashment_amount',
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
