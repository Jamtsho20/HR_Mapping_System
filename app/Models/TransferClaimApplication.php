<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $cast = ['attachment' => 'array'];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
