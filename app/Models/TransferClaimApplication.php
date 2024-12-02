<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferClaimApplication extends Model
{
    use HasFactory, CreatedByTrait;

    // protected $fillable = ['transfer_claim_no ', 'transfer_claim_id', 'current_location', 'new_location', 'distance_travelled', 'amount_claimed', 'attachment', 'status'];

    protected $guarded = [];

    protected $cast = ['attachment' => 'array'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function transfer_approved_by(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function type(){
        return $this->belongsTo(MasTransferClaim::class, 'transfer_claim_id');
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
