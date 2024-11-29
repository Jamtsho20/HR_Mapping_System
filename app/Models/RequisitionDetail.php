<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;

    public function requisition()
    {
        return $this->belongsTo(RequisitionApplication::class, 'requisition_id');
    }
}
