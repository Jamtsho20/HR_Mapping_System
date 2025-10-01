<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingList;
use App\Models\TrainingBudgetAllocation;
use Illuminate\Http\Request;

class TrainingBudgetAllocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training-application/training-budget,view')->only('index');
        $this->middleware('permission:training-application/training-budget,create')->only('store');
        $this->middleware('permission:training-application/training-budget,edit')->only('update');
        $this->middleware('permission:training-application/training-budget,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $trainingBudget = TrainingBudgetAllocation::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('training-application.training-budget.index', compact('privileges','trainingBudget'));
    }

    public function create()
    {
        $trainingLists = MasTrainingList::all();
        return view('training-application.training-budget.create',compact('trainingLists'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $types = new \App\Models\MasTrainingEvaluationType();
        $types->name = $request->name;
        $types->save();

        return redirect('training/training-evaluation-types')->with('msg_success', 'Training Evaluation Types created successfully');
    }

  
    public function edit(string $id)
    {
        $types = \App\Models\MasTrainingEvaluationType::findOrFail($id);
        return view('training.training-evaluation-types.edit', compact('types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);


        $timing = MasTrainingEvaluationType::findOrFail($id);
        $timing->name = $request->name;

        $timing->save();
        return redirect('training/training-evaluation-types')->with('msg_success', 'Training Evaluation Types updated successfully');
    }

  
    public function destroy($id)
    {
        try {
            MasTrainingevaluationType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training Evaluation types has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training Evaluation types cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
