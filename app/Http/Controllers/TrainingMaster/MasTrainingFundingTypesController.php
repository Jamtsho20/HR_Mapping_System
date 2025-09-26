<?php

namespace App\Http\Controllers\TrainingMaster;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingFundingType;
use Illuminate\Http\Request;

class MasTrainingFundingTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training/training-funding-types,view')->only('index');
        $this->middleware('permission:training/training-funding-types,create')->only('store');
        $this->middleware('permission:training/training-funding-types,edit')->only('update');
        $this->middleware('permission:training/training-funding-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $trainingFundingTypes = \App\Models\MasTrainingFundingType::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('training.training-funding-types.index', compact('privileges','trainingFundingTypes'));
    }

    public function create()
    {
        return view('training.training-funding-types.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $types = new \App\Models\MastrainingFundingType();
        $types->name = $request->name;
        $types->save();

        return redirect('training/training-funding-types')->with('msg_success', 'Training Types created successfully');
    }

  
    public function edit(string $id)
    {
        $types = \App\Models\MastrainingFundingType::findOrFail($id);
        return view('training.training-funding-types.edit', compact('types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);


        $timing = MasTrainingFundingType::findOrFail($id);
        $timing->name = $request->name;

        $timing->save();
        return redirect('training/training-funding-types')->with('msg_success', 'Training Types updated successfully');
    }

  
    public function destroy($id)
    {
        try {
            MasTrainingFundingType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training Funding Types has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training Funding Types cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
