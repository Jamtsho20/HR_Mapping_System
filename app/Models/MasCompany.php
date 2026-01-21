<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasCompany extends Model
{
    protected $table = 'mas_company';
    protected $fillable = [
        'name',
        'address',
        'code',
        'description',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function functions()
    {
        return $this->hasMany(FunctionModel::class, 'mas_company_id');
    }
}
