<?php

namespace App\Models;

use App\Models\MasApprovalRule;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasApprovalHead extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $table = 'mas_approval_heads';
    
    protected $fillable = [
        'name', 'description',];

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
        if ($request->has('description') && $request->query('description') != '') {
            $query->where('description', 'LIKE', '%' . $request->query('description') . '%');
        }
    }
    public function approvalRules() {
        return $this->hasMany(MasApprovalRule::class, 'mas_approval_head_id');
    }

}
