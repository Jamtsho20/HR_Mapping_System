<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'sifa_registration_id',
        'family_tree',
        'cid_of_dep_nom',
        'marriage_certificate',    
        'family_tree_spouse',    
        'spouse_cid',    
        'birth_certificate',    
        'adopted_children',    
        'if_divorced',
        'former_spouse' 
    ];
    protected $casts = [
        'cid_of_dep_nom' => 'array',
    ];

    public function SifaRegistration()
    {
        return $this->belongsTo(SifaRegistration::class, 'sifa_registration_id');
    }
}
