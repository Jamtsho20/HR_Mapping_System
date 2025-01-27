<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifaRegistration extends Model
{
    use HasFactory, CreatedByTrait;

    protected $fillable = [
        'mas_employee_id',  // Updated field name
        'sifa_type_id',
        'status',
        'is_registerd',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function type(){
        return $this->belongsTo(MasSifaType::class, 'sifa_type_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'mas_employee_id');
    }
    public function sifaNomination()
    {
        return $this->hasMany(SifaNomination::class, 'sifa_registration_id');
    }

    public function sifaDependent()
    {
        return $this->hasMany(SifaDependent::class, 'sifa_registration_id');
    }
    public function sifaDocument()
    {
        return $this->hasMany(SifaDocument::class, 'sifa_registration_id', 'id');
    }
    public function sifaRetirementAndNomination()
    {
        return $this->hasMany(SifaRetirementAndNomination::class, 'sifa_registration_id');
    }
    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }
    public function sifa_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function scopeFilter($query, $request, $onesOwnRecord = true)
    {
        if ($request->has('name') && $request->get('name') != '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('name') . '%');
            });
        }
    }

}
