<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetTypes extends Model
{
    use HasFactory,CreatedByTrait;
    protected $fillable = ['name'];

    public function budgetCode(){
        return $this->hasMany(BudgetCode::class);
    }
}
