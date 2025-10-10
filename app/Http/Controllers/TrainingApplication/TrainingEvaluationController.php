<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingList;
use App\Models\MasTrainingEvaluationType;
use App\Models\TrainingEvaluation;
use Illuminate\Http\Request;

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

        $evaluations = TrainingEvaluation::with(['trainingList', 'evaluationType', 'creator','children'])
            ->orderBy('sequence', 'asc')
            ->paginate(config('global.pagination'));

        // Add these two
        $trainingLists = MasTrainingList::select('id', 'title')->get();
        $evaluationTypes = MasTrainingEvaluationType::select('id', 'name')->get();

        return view('training-application.training-evaluations.index', compact(
            'privileges',
            'evaluations',
            'trainingLists',
            'evaluationTypes'
        ));
    }

    /**
     * Show the form for creating a new evaluation.
     */
    public function create()
    {
        $trainingLists = MasTrainingList::get(['id', 'title']);
        $evaluationTypes = MasTrainingEvaluationType::get(['id', 'name']);

        return view('training-application.training-evaluations.create', compact('trainingLists', 'evaluationTypes'));
    }

    /**
     * Store a newly created evaluation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_list_id' => 'required|exists:mas_training_lists,id',
            'evaluation_type_id' => 'required|exists:mas_training_evaluation_types,id',
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string',
            'sequences' => 'required|array|min:1',
            'sequences.*' => 'required|integer',
        ]);

        // Create the main title (parent question)
        $parent = TrainingEvaluation::create([
            'training_list_id' => $request->training_list_id,
            'evaluation_type_id' => $request->evaluation_type_id,
            'question' => $request->title,
            'sequence' => 1,
            'is_floated_to_trainees' => false,
            'created_by' => auth()->id(),
        ]);

        // Add sub-questions
        foreach ($request->questions as $index => $question) {
            TrainingEvaluation::create([
                'training_list_id' => $request->training_list_id,
                'evaluation_type_id' => $request->evaluation_type_id,
                'parent_id' => $parent->id,
                'question' => $question,
                'sequence' => $request->sequences[$index],
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('training-application.training-evaluations.index')
            ->with('success', 'Training evaluation questions created successfully!');
    }

    /**
     * Show the form for editing the specified evaluation.
     */
    public function edit($id)
    {
        $evaluation = TrainingEvaluation::findOrFail($id);
        $trainingLists = MasTrainingList::get(['id', 'title']);
        $evaluationTypes = MasTrainingEvaluationType::get(['id', 'name']);

        return view('training-application.training-evaluations.edit', compact(
            'evaluation',
            'trainingLists',
            'evaluationTypes'
        ));
    }

    /**
     * Update the specified evaluation in storage.
     */
public function update(Request $request, $id)
{
    $parent = TrainingEvaluation::findOrFail($id);

    $request->validate([
        'training_list_id' => 'required|exists:mas_training_lists,id',
        'evaluation_type_id' => 'required|exists:mas_training_evaluation_types,id',
        'title' => 'required|string|max:255',
        'questions' => 'required|array|min:1',
        'questions.*' => 'required|string',
        'sequences' => 'required|array|min:1',
        'sequences.*' => 'required|integer',
    ]);

    // Update parent question
    $parent->update([
        'training_list_id' => $request->training_list_id,
        'evaluation_type_id' => $request->evaluation_type_id,
        'question' => $request->title,
        'sequence' => 1,
        'updated_by' => auth()->id(),
    ]);

    $children = $parent->children()->orderBy('sequence')->get();

    foreach ($request->questions as $index => $questionText) {
        $sequence = $request->sequences[$index];

        if (isset($children[$index])) {
            // Update existing child
            $children[$index]->update([
                'question' => $questionText,
                'sequence' => $sequence,
                'updated_by' => auth()->id(),
            ]);
        } else {
            // Add new sub-question
            TrainingEvaluation::create([
                'training_list_id' => $request->training_list_id,
                'evaluation_type_id' => $request->evaluation_type_id,
                'parent_id' => $parent->id,
                'question' => $questionText,
                'sequence' => $sequence,
                'created_by' => auth()->id(),
            ]);
        }
    }

    return redirect()->route('training-application.training-evaluations.index')
        ->with('success', 'Training evaluation updated successfully!');
}


    /**
     * Remove the specified evaluation from storage.
     */
    public function destroy($id)
    {
        try {
            TrainingEvaluation::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training Evaluation has been deleted.');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training Evaluation cannot be deleted as it is used in other modules. Contact system admin.');
        }
    }
}
