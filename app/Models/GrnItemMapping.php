<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnItemMapping extends Model
{
    protected $fillable = [
        'grn_no',
        'last_synced_at',
        'status'
    ];
    use HasFactory;



   public function employee()
   {
        return $this->belongsTo(User::class, 'created_by');
   }

   
}
