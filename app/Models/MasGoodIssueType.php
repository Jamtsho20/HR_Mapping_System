<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasGoodIssueType extends Model
{
    use HasFactory, CreatedByTrait;

    public function approvableRule() // relationship with mas_approvable_rules
    {
        return $this->morphMany(MasApprovalRule::class, 'approvable');
    }

    public function goodIssues () 
    {
        return $this->hasMany(GoodIssueApplication::class, 'issue_type_id');
    }
}
