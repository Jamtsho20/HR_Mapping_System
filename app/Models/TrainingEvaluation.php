<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasTrainingList;
use App\Models\MasTrainingEvaluationType;
use App\Models\MasEmployee;

class TrainingEvaluation extends Model
{
    use HasFactory;

    protected $table = 'training_evaluations';

    protected $fillable = [
        'training_list_id',
        'evaluation_type_id',
        'parent_id',
        'title',
        'question_type',
        'is_floated_to_trainees',
        'question',
        'sequence',
        'created_by',
        'updated_by',
    ];


    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TrainingEvaluation::class, 'parent_id')->orderBy('sequence');
    }

    public function options()
    {
        return $this->hasMany(TrainingEvaluationOption::class, 'evaluation_id')->orderBy('sequence');
    }
    public function assignedEmployees()
    {
        return $this->belongsToMany(User::class, 'training_evaluation_employee', 'evaluation_id', 'employee_id')->withTimestamps();
    }

    /**
     * Relationship: Evaluation belongs to a Training List
     */
    public function trainingList()
    {
        return $this->belongsTo(MasTrainingList::class, 'training_list_id');
    }


    /**
     * Relationship: Evaluation belongs to an Evaluation Type
     */
    public function evaluationType()
    {
        return $this->belongsTo(MasTrainingEvaluationType::class, 'evaluation_type_id');
    }


    public function answers()
    {
        return $this->hasMany(TrainingEvaluationAnswer::class, 'evaluation_id')
            ->with('createdBy');
    }

    /**
     * Relationship: Created by employee
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Alias for blade/template compatibility
    public function creator()
    {
        return $this->createdBy();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    /**
     * Scope: Filter query by request parameters
     */
    public function scopeFilter($query, $request)
    {
        if ($request->filled('training_list_id')) {
            $query->where('training_list_id', $request->query('training_list_id'));
        }

        if ($request->filled('evaluation_type_id')) {
            $query->where('evaluation_type_id', $request->query('evaluation_type_id'));
        }

        if ($request->filled('question')) {
            $query->where('question', 'LIKE', '%' . $request->query('question') . '%');
        }

        if ($request->filled('sequence')) {
            $query->where('sequence', $request->query('sequence'));
        }
    }
}
