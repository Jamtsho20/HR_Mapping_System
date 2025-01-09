<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasConditionField extends Model
{
    use HasFactory;

    public function approval_head()
    {
        return $this->belongsTo(MasApprovalHead::class, 'mas_approval_head_id');
    }
}
