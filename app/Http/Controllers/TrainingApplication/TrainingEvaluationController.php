<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingList;
use App\Models\MasTrainingEvaluationType;
use App\Models\TrainingEvaluation;
use App\Models\User;

use App\Models\TrainingEvaluationOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training-application/training-evaluations,view')->only('index');
        $this->middleware('permission:training-application/training-evaluations,create')->only('store');
        $this->middleware('permission:training-application/training-evaluations,edit')->only('update');
        $this->middleware('permission:training-application/training-evaluations,delete')->only('destroy');
    }

    /**
     * Display a listing of training evaluations.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $evaluationTypes = MasTrainingEvaluationType::select('id', 'name')->get();
        $employees = User::select('id', 'name', 'username')->orderBy('name')->get();
        $trainingLists = MasTrainingList::select('id', 'title')->get();

        $hasFilter = $request->filled('evaluation_type_id');

        if ($hasFilter) {
            $evaluations = TrainingEvaluation::with(['trainingList', 'evaluationType', 'creator', 'children.options'])
                ->whereNull('parent_id')
                ->where('evaluation_type_id', $request->evaluation_type_id)
                ->orderBy('sequence', 'asc')
                ->paginate(config('global.pagination'))
                ->appends($request->query());
        } else {
            $evaluations = null;
        }

        return view('training-application.training-evaluations.index', compact(
            'privileges',
            'evaluations',
            'trainingLists',
            'evaluationTypes',
            'employees',
            'hasFilter'
        ));
    }


    /**
     * Show the form for creating a new evaluation.
     */
    public function create()
    {
        // $trainingLists = MasTrainingList::get(['id', 'title']);
        $evaluationTypes = MasTrainingEvaluationType::get(['id', 'name']);

        return view('training-application.training-evaluations.create', compact('evaluationTypes'));
    }

    /**
     * Store a newly created evaluation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'evaluation_type_id' => 'required|exists:mas_training_evaluation_types,id',
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:short_answer,scale,option',
            'questions.*.sequence' => 'required|integer',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // Create the main title (parent evaluation)
            $parent = TrainingEvaluation::create([
                'evaluation_type_id' => $request->evaluation_type_id,
                'title' => $request->title,
                'sequence' => 1,
                'is_floated_to_trainees' => false,
                'created_by' => auth()->id(),
            ]);

            // Add sub-questions
            foreach ($request->questions as $q) {
                $question = TrainingEvaluation::create([
                    'evaluation_type_id' => $request->evaluation_type_id,
                    'parent_id' => $parent->id,
                    'question' => $q['text'],
                    'question_type' => $q['type'],
                    'sequence' => $q['sequence'],
                    'created_by' => auth()->id(),
                ]);

                // Add options if question type = "option"
                if ($q['type'] === 'option' && !empty($q['options'])) {
                    foreach ($q['options'] as $index => $optionText) {
                        TrainingEvaluationOption::create([
                            'evaluation_id' => $question->id,
                            'option_text' => $optionText,
                            'sequence' => $index + 1,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('training-application.training-evaluations.index')
            ->with('success', 'Training evaluation questions created successfully!');
    }

    public function assign(Request $request)
    {
        // Validate input
        $request->validate([
            'evaluation_id' => 'required|exists:training_evaluations,id',
            'employee_ids'  => 'nullable|array',
            'employee_ids.*' => 'exists:mas_employees,id',
        ]);

        $evaluation = TrainingEvaluation::findOrFail($request->evaluation_id);

        // Sync employees (many-to-many relation assumed)
        // Assumes you have a relationship like: $evaluation->assignedEmployees()
        $evaluation->assignedEmployees()->sync($request->employee_ids ?? []);

        return redirect()->back()->with('success', 'Employees assigned successfully.');
    }
    public function unassign(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:training_evaluations,id',
            'employee_id' => 'required|exists:mas_employees,id',
        ]);

        $evaluation = TrainingEvaluation::findOrFail($request->evaluation_id);
        $evaluation->assignedEmployees()->detach($request->employee_id);

        return response()->json(['success' => true]);
    }


    /**
     * Show the form for editing an existing evaluation.
     */
    public function edit($id)
    {
        $evaluation = TrainingEvaluation::with('children.options')->findOrFail($id);
        $trainingLists = MasTrainingList::get(['id', 'title']);
        $evaluationTypes = MasTrainingEvaluationType::get(['id', 'name']);

        return view('training-application.training-evaluations.edit', compact(
            'evaluation',
            'trainingLists',
            'evaluationTypes'
        ));
    }

    /**
     * Update an existing evaluation.
     */
    public function update(Request $request, $id)
    {
        $parent = TrainingEvaluation::findOrFail($id);

        $request->validate([
            'evaluation_type_id' => 'required|exists:mas_training_evaluation_types,id',
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:short_answer,scale,option',
            'questions.*.sequence' => 'required|integer',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $parent) {
            // Update parent
            $parent->update([
                'evaluation_type_id' => $request->evaluation_type_id,
                'title' => $request->title,
                'updated_by' => auth()->id(),
            ]);

            // Delete existing children to simplify logic
            $parent->children()->each(function ($child) {
                $child->options()->delete();
                $child->delete();
            });

            // Recreate children
            foreach ($request->questions as $q) {
                $question = TrainingEvaluation::create([
                    'evaluation_type_id' => $request->evaluation_type_id,
                    'parent_id' => $parent->id,
                    'question' => $q['text'],
                    'question_type' => $q['type'],
                    'sequence' => $q['sequence'],
                    'created_by' => auth()->id(),
                ]);

                if ($q['type'] === 'option' && !empty($q['options'])) {
                    foreach ($q['options'] as $index => $optionText) {
                        TrainingEvaluationOption::create([
                            'evaluation_id' => $question->id,
                            'option_text' => $optionText,
                            'sequence' => $index + 1,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('training-application.training-evaluations.index')
            ->with('success', 'Training evaluation updated successfully!');
    }

    /**
     * Delete an evaluation (and children + options).
     */
    public function destroy($id)
    {
        try {
            $evaluation = TrainingEvaluation::with('children.options')->findOrFail($id);

            DB::transaction(function () use ($evaluation) {
                foreach ($evaluation->children as $child) {
                    $child->options()->delete();
                    $child->delete();
                }
                $evaluation->delete();
            });

            return back()->with('msg_success', 'Training Evaluation has been deleted.');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training Evaluation cannot be deleted as it is used in other modules. Contact system admin.');
        }
    }
}
