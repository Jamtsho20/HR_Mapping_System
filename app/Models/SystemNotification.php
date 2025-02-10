<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $table = 'system_notifications';

    protected $fillable = [
        'title',
        'message',
        'created_by',
        'updated_by',
    ];

}
