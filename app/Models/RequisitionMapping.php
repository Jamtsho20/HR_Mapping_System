<?php

namespace App\Models;
use App\Traits\CreatedByTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionMapping extends Model
{
    use HasFactory, CreatedByTrait;
    protected $fillable = [
        'requisition_id', 'grn_item_mapping_id'
    ];
    
}
