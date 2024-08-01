<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasApprovalRule extends Model
{
    use HasFactory;

    public function approvable(){
        return $this->morphTo();
    }
}
