<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasAttendanceFeature extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'name', 'description', 'is_mandatory', 'status'
    ];
}
