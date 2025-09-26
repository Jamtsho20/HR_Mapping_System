<?php

namespace App\Http\Controllers\TrainingMaster;

use App\Http\Controllers\Controller;
use App\Models\MasCountry;
use App\Models\MasDepartment;
use App\Models\MasDzongkhag;
use App\Models\MasTrainingFundingType;
use App\Models\MasTrainingList;
use App\Models\MasTrainingNature;
use App\Models\MasTrainingType;
use Illuminate\Http\Request;

class MasTrainingListController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training/training-lists,view')->only('index');
        $this->middleware('permission:training/training-lists,create')->only('store');
        $this->middleware('permission:training/training-lists,edit')->only('update');
        $this->middleware('permission:training/training-lists,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();

        $trainingLists = MasTrainingList::filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'));

        return view('training.training-lists.index', compact('privileges', 'trainingLists'));
    }

    public function create()
    {
        $trainingTypes = MasTrainingType::get(['id', 'name']);
        $trainingNatures = MasTrainingNature::get(['id', 'name']);
        $fundingTypes = MasTrainingFundingType::get(['id', 'name']);
        $country = MasCountry::get(['id', 'name']);
        $dzonkhag = MasDzongkhag::get(['id', 'dzongkhag']);
        $department = MasDepartment::where('status', 1)->get(['id', 'name']);
        return view('training.training-lists.create', compact('trainingTypes', 'fundingTypes', 'country', 'dzonkhag', 'department','trainingNatures'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:mas_training_types,id',
            'training_nature_id' => 'required|integer|exists:mas_training_natures,id',
            'funding_type_id' => 'required|integer|exists:mas_training_funding_types,id',
            'country_id' => 'nullable|integer|exists:mas_countries,id',
            'dzongkhag_id' => 'nullable|integer|exists:mas_dzongkhags,id',
            'location' => 'nullable|string|max:255',
            'institute' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'department_id' => 'required|integer|exists:mas_departments,id',
            'amount_allotted' => 'nullable|numeric|min:0',
        ]);

        $training = new \App\Models\MasTrainingList();
        $training->title = $request->title;
        $training->type_id = $request->type_id;
        $training->training_nature_id = $request->training_nature_id;
        $training->funding_type_id = $request->funding_type_id;
        $training->country_id = $request->country_id;
        $training->dzongkhag_id = $request->dzongkhag_id;
        $training->location = $request->location;
        $training->institute = $request->institute;
        $training->start_date = $request->start_date;
        $training->end_date = $request->end_date;
        $training->department_id = $request->department_id;
        $training->amount_allocated = $request->amount_allotted;
        $training->created_by = auth()->user()->id;
        $training->save();

        return redirect()->route('training-lists.index')->with('success', 'Training List created successfully.');
    }


    public function edit($id)
    {
        $trainingList = MasTrainingList::findOrFail($id);

        // Load related dropdown data
        $trainingTypes = MasTrainingType::get(['id', 'name']);;
        $trainingNatures = MasTrainingNature::get(['id', 'name']);;
        $fundingTypes = MasTrainingFundingType::get(['id', 'name']);;
        $countries = MasCountry::get(['id', 'name']);;
        $dzongkhags = MasDzongkhag::get(['id', 'dzongkhag']);;

        return view('training.training-lists.edit', compact(
            'trainingList', 
            'trainingTypes', 
            'trainingNatures', 
            'fundingTypes', 
            'countries', 
            'dzongkhags'
        ));
    }

    /**
     * Update the specified training list in storage.
     */
    public function update(Request $request, $id)
    {
        $trainingList = MasTrainingList::findOrFail($id);

        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'type_id' => 'required|exists:mas_training_types,id',
            'training_nature_id' => 'required|exists:mas_training_natures,id',
            'funding_type_id' => 'required|exists:mas_training_funding_types,id',
            'country_id' => 'nullable|exists:mas_countries,id',
            'dzongkhag_id' => 'nullable|exists:mas_dzongkhags,id',
            'location' => 'nullable|string|max:255',
            'institute' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount_allocated' => 'nullable|numeric',
        ]);

        // Adjust fields based on type (in-country/ex-country)
        if ($request->type_id == 1) { // In-country
            $countryId = null;
            $dzongkhagId = $request->dzongkhag_id;
        } elseif ($request->type_id == 2) { // Ex-country
            $countryId = $request->country_id;
            $dzongkhagId = null;
        } else {
            $countryId = null;
            $dzongkhagId = null;
        }

        // Update training list
        $trainingList->update([
            'title' => $request->title,
            'type_id' => $request->type_id,
            'training_nature_id' => $request->training_nature_id,
            'funding_type_id' => $request->funding_type_id,
            'country_id' => $countryId,
            'dzongkhag_id' => $dzongkhagId,
            'location' => $request->location,
            'institute' => $request->institute,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'amount_allocated' => $request->amount_allocated,
        ]);

        return redirect()->route('training-lists.index')
                         ->with('success', 'Training List updated successfully.');
    }


    public function destroy($id)
    {
        try {
            MasTrainingList::findOrFail($id)->delete();

            return back()->with('msg_success', 'Training lists has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Training lists cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
