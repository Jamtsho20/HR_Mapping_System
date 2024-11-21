<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsaClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $guarded = [];

    protected $cast = [
        'attachment' => 'array'
    ];

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function dsaClaimDetails() {
        return $this->hasMany(DsaClaimDetail::class, 'dsa_claim_id');
    }

    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('mas_expense_type_id') && $request->query('mas_expense_type_id') != '') {
            $query->where('mas_expense_type_id', $request->query('mas_expense_type_id'));
        }

        if($onesOwnRecord){
            $query->where('created_by', auth()->user()->id);
        }
    }
}
