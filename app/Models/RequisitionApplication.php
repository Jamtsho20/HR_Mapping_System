<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function requisitioinType()
    {
        return $this->belongsTo(MasRequisitionType::class, 'requisition_type_id');
    }

    public function details() 
    {
        return $this->hasMany(RequisitionApplication::class, 'requisition_id');
    }
}
