<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    use HasFactory;
    protected $table = 'attendance_statuses';

    protected $fillable = [
        'code',
        'description',
        'color',
    ];

    public function getAttendanceStatusAttribute() //combination of title and full name while display
    {
        return $this->code . '-' . $this->description;
    }
}
