<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;


class AssetCommissionApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'id',
        'commission_no',
        'receipt_no',
        'commission_date',
        'file',
        'status'
    ];

    public function commisionType ()
    {
        return $this->belongsTo(MasCommissionTypes::class, 'commission_type_id');
    }
    public function details ()
    {
        return $this->hasMany(AssetCommissionDetail::class, 'commission_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function empJob(){
        return $this->hasOne(MasEmployeeJob::class, 'mas_employee_id');
    }

    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
}
