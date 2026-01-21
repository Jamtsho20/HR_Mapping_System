<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionModel extends Model
{
    use HasFactory;

    protected $table = 'mas_function';

    protected $fillable = [
        'name',
        'description',
        'mas_company_id',
        'approved_strength',
        'current_strength',
        'status',
    ];
    public function company()
    {
        return $this->belongsTo(MasCompany::class, 'mas_company_id');
    }
    public function designations()
    {
        return $this->hasMany(MasDesignation::class, 'mas_function_id');
    }
}
