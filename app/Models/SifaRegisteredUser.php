<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaRegisteredUser extends Model
{
    use HasFactory, CreatedByTrait;
    public function scopeFilter($query, $request)
    {
        if ($request->has('employee') && $request->query('employee') != '') {
            $query->where('mas_employee_id', $request->query('employee'));
        }
    }
}
