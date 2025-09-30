<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasTrainingList extends Model
{
    use HasFactory, CreatedByTrait;

    protected $table = 'mas_training_lists';

    protected $fillable = [
        'type_id',
        'training_nature_id',
        'funding_type_id',
        'title',
        'country_id',
        'dzongkhag_id',
        'location',
        'institute',
        'start_date',
        'end_date',
        'amount_allocated',
    ];

    public function trainingType()
    {
        return $this->belongsTo(MasTrainingType::class, 'type_id');
    }

    public function trainingNature()
    {
        return $this->belongsTo(MasTrainingNature::class, 'training_nature_id');
    }

    public function fundingType()
    {
        return $this->belongsTo(MasTrainingFundingType::class, 'funding_type_id');
    }

    public function country()
    {
        return $this->belongsTo(MasCountry::class, 'country_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }

    public function department()
    {
        return $this->belongsTo(MasDepartment::class, 'department_id');
    }

    public function budget()
    {
        return $this->hasMany(TrainingBudgetAllocation::class, 'training_list_id');
    }

    /**
     * Get the training list's bonds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bond()
    {
        return $this->hasMany(TrainingBond::class, 'training_list_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
