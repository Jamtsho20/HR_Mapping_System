<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\TrainingEvaluation;
use App\Models\TrainingEvaluationAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyEvaluationAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training-application/my-evaluations,view')->only('index');
        $this->middleware('permission:training-application/my-evaluations,create')->only('store');
        $this->middleware('permission:training-application/my-evaluations,edit')->only('update');
        $this->middleware('permission:training-application/my-evaluations,delete')->only('destroy');
    }

    /**
     * Display a listing of evaluations assigned to the current user.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized');

        $evaluations = TrainingEvaluation::whereHas('assignedEmployees', function ($q) use ($user) {
            $q->where('training_evaluation_employee.employee_id', $user->id);
        })
            ->with([
                'answers' => function ($q) use ($user) {
                    $q->where('created_by', $user->id)->with('creator');
                },
                'children.options'
            ])
            ->whereNull('parent_id')
            ->orderByDesc('id')
            ->paginate(10);

        $privileges = $request->instance(); // optional for buttons

        return view('training-application.my-evaluations.index', compact('evaluations', 'privileges'));
    }

    /**
     * Show the form for creating a new answer for a specific evaluation.
     */
    public function create(TrainingEvaluation $evaluation)
    {
        // $evaluation now contains the model with all children & options
        $evaluation->load([
            'children.options',
            'children.answers' => function ($query) {
                $query->where('created_by', auth()->id());
            },
        ]);

        return view('training-application.my-evaluations.create', compact('evaluation'));
    }


    /**
     * Store answers for the evaluation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:training_evaluations,id',
            'answers' => 'required|array'
        ]);

        $user = auth()->user();
        $evaluationId = $request->evaluation_id;
        $answers = $request->answers;

        DB::transaction(function () use ($answers, $user, $evaluationId) {
            foreach ($answers as $subQuestionId => $answerValue) {
                TrainingEvaluationAnswer::updateOrCreate(
                    [
                        'evaluation_id' => $subQuestionId, // updated column
                        'created_by' => $user->id
                    ],
                    [
                        'answer' => $answerValue
                    ]
                );
            }
        });

        return redirect()->route('training-application.my-evaluations.index')
            ->with('success', 'Answers submitted successfully!');
    }


    /**
     * Delete an answer submitted by the user.
     */
    // In App\Http\Controllers\TrainingApplication\MyEvaluationAnswerController.php

    /**
     * Delete ALL answers submitted by the user for a specific evaluation.
     */
    public function destroy($evaluationId)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized');

        // 1. Find the main evaluation and get all its sub-question IDs (children)
        $evaluation = TrainingEvaluation::findOrFail($evaluationId);
        $subQuestionIds = $evaluation->children()->pluck('id');

        // Abort if no questions exist to prevent deleting answers from other evaluations
        if ($subQuestionIds->isEmpty()) {
            return back()->with('info', 'This evaluation has no questions and therefore no answers to delete.');
        }

        // 2. Perform a mass delete of all answers by this user, linked to these sub-questions
        $deletedCount = TrainingEvaluationAnswer::whereIn('evaluation_id', $subQuestionIds)
            ->where('created_by', $user->id)
            ->delete();

        return back()->with('success', "All {$deletedCount} answers for '{$evaluation->title}' have been successfully deleted.");
    }
}
