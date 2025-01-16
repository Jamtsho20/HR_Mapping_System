<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaRegisteredUser extends Model
{
    use HasFactory,CreatedByTrait;
}
