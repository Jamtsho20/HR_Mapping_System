<?php

namespace App\Http\Controllers\TrainingMaster;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingType;
use Illuminate\Http\Request;

class MasTrainingTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training/training-types,view')->only('index');
        $this->middleware('permission:training/training-types,create')->only('store');
        $this->middleware('permission:training/training-types,edit')->only('update');
        $this->middleware('permission:training/training-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $trainingTypes = \App\Models\MastrainingType::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('training.training-types.index', compact('privileges','trainingTypes'));
    }

    public function create()
    {
        return view('training.training-types.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $types = new \App\Models\MastrainingType();
        $types->name = $request->name;
        $types->save();

        return redirect('training/training-types')->with('msg_success', 'Training Types created successfully');
    }

  
    public function edit(string $id)
    {
        $types = \App\Models\MastrainingType::findOrFail($id);
        return view('training.training-types.edit', compact('types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);


        $timing = MasTrainingType::findOrFail($id);
        $timing->name = $request->name;

        $timing->save();
        return redirect('training/training-types')->with('msg_success', 'Training Types updated successfully');
    }

  
    public function destroy($id)
    {
        try {
            MasTrainingType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training Types has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training Types cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
