<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'level', 'status', 'remarks', 'created_by', 'approved_by', 'rejected_by', 'cancelled_by', 'updated_by'
    ];

    public function application()
    {
        return $this->morphTo();
    }
}
