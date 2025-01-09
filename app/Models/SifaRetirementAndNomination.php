<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaRetirementAndNomination extends Model
{
    use HasFactory;
    protected $table = 'sifa_and_retirement_nominations'; 
    
    protected $fillable = [
        'sifa_registration_id',
        'is_registered',
        'relation_with_employee',
        'cid_number',
        'percentage_of_share',
        'nominee_name',
    ];
    
    public function sifaRegistration()
    {
        return $this->belongsTo(SifaRegistration::class, 'sifa_registration_id');
    }
}
