<?php

namespace App\Http\Controllers\TrainingMaster;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingExpenseType;
use Illuminate\Http\Request;

class MasTrainingExpenseTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training/training-expense-types,view')->only('index');
        $this->middleware('permission:training/training-expense-types,create')->only('store');
        $this->middleware('permission:training/training-expense-types,edit')->only('update');
        $this->middleware('permission:training/training-expense-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $trainingExpenseTypes = \App\Models\MasTrainingExpenseType::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('training.training-expense-types.index', compact('privileges','trainingExpenseTypes'));
    }

    public function create()
    {
        return view('training.training-expense-types.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $types = new \App\Models\MasTrainingExpenseType();
        $types->name = $request->name;
        $types->save();

        return redirect('training/training-expense-types')->with('msg_success', 'Training Expense Types created successfully');
    }

  
    public function edit(string $id)
    {
        $types = \App\Models\MasTrainingExpenseType::findOrFail($id);
        return view('training.training-expense-types.edit', compact('types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);


        $timing = MasTrainingExpenseType::findOrFail($id);
        $timing->name = $request->name;

        $timing->save();
        return redirect('training/training-expense-types')->with('msg_success', 'Training Types updated successfully');
    }

  
    public function destroy($id)
    {
        try {
            MasTrainingExpenseType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training expense types has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training expense types cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
