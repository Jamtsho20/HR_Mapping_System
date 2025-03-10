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


    public function detail()
    {
        return $this->hasMany(ItemMappingDetail::class, 'mapping_id'); 
    }


   public function employee()
   {
        return $this->belongsTo(User::class, 'created_by');
   }

   public function scopeFilter($query, $request)
   {
        if ($request->has('grn_no') && $request->query('grn_no') != '') {
            $query->where('grn_no', $request->query('grn_no'));
        }
   }


}
