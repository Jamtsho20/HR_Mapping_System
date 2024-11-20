<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaNomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'sifa_registration_id',
        'nominee_name',
        'relation_with_employee',
        'cid_number',
        'percentage_of_share',
    ];
    
    public function sifaRegistration()
    {
        return $this->belongsTo(SifaRegistration::class, 'sifa_registration_id');
    }
}
