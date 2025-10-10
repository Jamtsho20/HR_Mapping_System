<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\TrainingEvaluation;
use App\Models\TrainingEvaluationAnswer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvaluationAnswersExport;

class TrainingEvaluationAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training-application/training-evaluations-answers,view')->only('index');
        $this->middleware('permission:training-application/training-evaluations-answers,create')->only('store');
        $this->middleware('permission:training-application/training-evaluations-answers,edit')->only('update');
        $this->middleware('permission:training-application/training-evaluations-answers,delete')->only('destroy');
        $this->middleware('permission:training-application/training-evaluations-answers,export')->only('export');
    }

    /**
     * Display a listing of evaluation answers.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        // First, get all evaluations with their related answers and creators
        $evaluations = TrainingEvaluation::with(['answers.creator'])
            ->orderBy('id', 'desc')
            ->paginate(config('global.pagination')); // paginate by questions instead of answers

        return view(
            'training-application.training-evaluations-answers.index',
            compact('privileges', 'evaluations')
        );
    }


    /**
     * Show the form for creating a new answer.
     */
    public function create()
    {
        $evaluations = TrainingEvaluation::with('children')
            ->whereNull('parent_id')
            ->get();

        return view('training-application.training-evaluations-answers.create', compact('evaluations'));
    }

    /**
     * Store a newly created answer in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'main_question' => 'required|exists:training_evaluations,id',
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        foreach ($request->answers as $questionId => $answerText) {
            TrainingEvaluationAnswer::create([
                'evaluation_id' => $questionId,
                'answer' => $answerText,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('training-application.training-evaluations-answers.index')
            ->with('success', 'Answers submitted successfully!');
    }


    /**
     * Show the form for editing the specified answer.
     */
    public function edit($id)
    {
        $answer = TrainingEvaluationAnswer::findOrFail($id);
        $evaluations = TrainingEvaluation::select('id', 'question')->get();

        return view('training-application.training-evaluations-answers.edit', compact('answer', 'evaluations'));
    }

    /**
     * Update the specified answer in storage.
     */
    public function update(Request $request, $id)
    {
        $answer = TrainingEvaluationAnswer::findOrFail($id);

        $request->validate([
            'evaluation_id' => 'required|exists:training_evaluations,id',
            'answer' => 'required|string',
        ]);

        $answer->update([
            'evaluation_id' => $request->evaluation_id,
            'answer' => $request->answer,
            'updated_by' => auth()->user()->id,
        ]);

        return redirect()->route('training-application.training-evaluations-answers.index')
            ->with('success', 'Evaluation Answer updated successfully.');
    }

    /**
     * Remove the specified answer from storage.
     */
    public function destroy($id)
    {
        try {
            TrainingEvaluationAnswer::findOrFail($id)->delete();

            return back()->with('msg_success', 'Evaluation Answer has been deleted.');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Answer cannot be deleted as it is used in other modules. Contact system admin.');
        }
    }

    public function export()
    {
        return Excel::download(new EvaluationAnswersExport, 'evaluation_answers.xlsx');
    }
}
