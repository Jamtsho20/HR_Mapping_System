<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasEmployeePermenantAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'mas_employee_id', 'mas_dzongkhag_id', 'mas_gewog_id', 'mas_village_id', 'thram_no', 'house_no'    
    ];

    public function masEmployee(){
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function masDzongkhag()
    {
        return $this->hasOne(MasDzongkhag::class, 'id');
    }
}
