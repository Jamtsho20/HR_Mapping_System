<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingApplication extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'training_applications';

    protected $fillable = [
        'training_list_id',
        'type_id',
        'is_self_funded',
        'status',
        'updated_by',
        'created_by',
    ];

    public function trainingList()
    {
        return $this->belongsTo(MasTrainingList::class, 'training_list_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function trainees()
    {
        return $this->hasMany(TraineeList::class, 'training_application_id');
    }

    public function type()
    {
        return $this->belongsTo(MasTrainingType::class, 'type_id');
    }
    public function histories()
    {
        return $this->morphMany(ApplicationHistory::class, 'application');
    }
    public function audit_logs()
    {
        return $this->morphMany(ApplicationAuditLog::class, 'application');
    }
    public function training_approved_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
