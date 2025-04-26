<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'delegator_id', 'role_id', 'delegatee_id', 'module', 'start_date', 'end_date', 'remark', 'status' 
    ];

    public function delegator()
    {
        return $this->belongsTo(User::class, 'delgator_id');
    }

    public function delegatee()
    {
        return $this->belongsTo(User::class, 'delgatee_id');
    }

    public function scopeFilter($query, $request)
    {
        // if ($request->has('') && $request->query('gewog') != '')
        // {
        //     $query->where('name', 'LIKE', '%' . $request->query('gewog') . '%');
        // }
        // if ($request->has('dzongkhag') && $request->query('dzongkhag') != '')
        // {
        //     $query->where('mas_dzongkhag_id', $request->query('dzongkhag'));
        // }
    }

}
