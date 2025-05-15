<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEncashment extends Model
{
    use HasFactory;

    protected $table = 'leave_encashment_mail_table';

    protected $fillable = [
        'mas_employee_id',
        'email_sent',
        'sent_at',
    ];

}
