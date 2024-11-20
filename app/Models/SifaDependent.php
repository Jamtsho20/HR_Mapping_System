<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaDependent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sifa_registration_id',
        'dependent_name',
        'relation_with_employee',
        'cid_number',
    ];
    public function sifaRegistration()
    {
        return $this->belongsTo(SifaRegistration::class, 'sifa_registration_id');
    }
}
