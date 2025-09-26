<?php

namespace App\Http\Controllers\TrainingMaster;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingNature;
use Illuminate\Http\Request;

class MasTrainingNatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training/training-natures,view')->only('index');
        $this->middleware('permission:training/training-natures,create')->only('store');
        $this->middleware('permission:training/training-natures,edit')->only('update');
        $this->middleware('permission:training/training-natures,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $trainingNatures = \App\Models\MasTrainingNature::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('training.training-natures.index', compact('privileges','trainingNatures'));
    }

    public function create()
    {
        return view('training.training-natures.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $types = new \App\Models\MasTrainingNature();
        $types->name = $request->name;
        $types->save();

        return redirect('training/training-natures')->with('msg_success', 'Training Types created successfully');
    }

  
    public function edit(string $id)
    {
        $types = \App\Models\MasTrainingNature::findOrFail($id);
        return view('training.training-natures.edit', compact('types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);


        $timing = MasTrainingNature::findOrFail($id);
        $timing->name = $request->name;

        $timing->save();
        return redirect('training/training-natures')->with('msg_success', 'Training Types updated successfully');
    }

  
    public function destroy($id)
    {
        try {
            MasTrainingNature::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training Natures has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training Natures cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
