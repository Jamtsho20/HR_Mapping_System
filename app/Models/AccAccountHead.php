<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountHead extends Model
{
    use HasFactory;
    
    use HasFactory, HasUuids;
    protected $fillable = [
        "id",
        "code",
        "name",
        "type",
        "created_by",
        "edited_by"
    ];

}
