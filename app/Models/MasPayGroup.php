<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;

class MasPayGroup extends Model
{
    use HasFactory, CreatedByTrait;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
        
        if ($request->has('applicable_on') && $request->query('applicable_on') != '') {
            $query->where('applicable_on', $request->query('applicable_on'));
        }
    }
    public function payGroupDetails()
    {
        return $this->hasMany(MasPayGroupDetail::class, 'mas_pay_group_id');
    }
}
