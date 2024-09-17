<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasPayGroupDetail extends Model
{
    use HasFactory,CreatedByTrait;

    protected $fillable = [
        'mas_pay_group_id', 'mas_grade_id', 'calculation_method', 'amount'
    ];
    
    public function masPayGroup(){
        return $this->belongsTo(MasPayGroup::class);
    }
    public function scopeFilter($query, $request)
    {
        if ($request->has('calculation_method') && $request->query('calculation_method') != '') {
            $query->where('calculation_method', '>=', $request->query('calculation_method'));
        }

        if ($request->has('amount') && $request->query('amount') != '') {
            $query->where('amount', '<=', $request->query('amount'));
        }
    }
    public function grade()
    {   
    return $this->belongsTo(MasGrade::class, 'mas_grade_id');
    }  
    // public function getCalculationMethodTextAttribute()
    // {
    //     $methods = [
    //         1 => 'Actual Method',
    //         2 => 'Division',
    //         3 => 'Percentage',
    //     ];

    //     return $methods[$this->calculation_method] ?? 'Unknown Method';
    // } 
    public static function getCalculationMethods()
    {
    return [
        1 => 'Actual Method',
        2 => 'Division',
        3 => 'Percentage',
    ];
    }

}
