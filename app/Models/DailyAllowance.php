<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAllowance extends Model
{
    use HasFactory,CreatedByTrait;

    protected $table = 'mas_daily_allowances';
    
    protected $fillable = [
        'mas_grade_id', 'da_in_country', 'da_india_capital','da_india_non_capital','da_third_country'
    ];

    public function grade()
    {
        return $this->belongsTo(MasGrade::class, 'mas_grade_id');
    }

}
