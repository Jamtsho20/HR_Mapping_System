<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasApprovalHead extends Model
{
    use HasFactory;

    public function approvalRules() {
        return $this->hasMany(MasApprovalRule::class, 'mas_approval_head_id');
    }
}
