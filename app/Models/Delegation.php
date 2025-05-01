<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Delegation extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'delegator_id', 'role_id', 'delegatee_id', 'module', 'start_date', 'end_date', 'remark', 'status' 
    ];

    public function delegator()
    {
        return $this->belongsTo(User::class, 'delegator_id');
    }

    public function delegatee()
    {
        return $this->belongsTo(User::class, 'delegatee_id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    //accessors & mutators refeer this
    public function getStatusNameAttribute()
    {
        $statusNameMapping = config('global.status');
        return $statusNameMapping[$this->status];
    }

    //scope filter
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($onesOwnRecord) {
            $query->where('created_by', auth()->user()->id);
        }
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
