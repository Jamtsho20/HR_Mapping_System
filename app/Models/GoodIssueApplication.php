<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodIssueApplication extends Model
{
    use HasFactory, CreatedByTrait;

    public function issueType () 
    {
        return $this->belongsTo(MasGoodIssueType::class, 'issue_type_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
