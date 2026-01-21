<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SystemNotification extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $table = 'system_notifications';

    protected $fillable = [
        'mas_employee_id',
        'title',
        'message',
        'created_by',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }

}
