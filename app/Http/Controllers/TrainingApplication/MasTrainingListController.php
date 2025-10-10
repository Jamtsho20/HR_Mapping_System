<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\MasCountry;
use App\Models\MasDepartment;
use App\Models\MasDzongkhag;
use App\Models\MasTrainingExpenseType;
use App\Models\MasTrainingFundingType;
use App\Models\MasTrainingList;
use App\Models\MasTrainingNature;
use App\Models\MasTrainingType;
use Illuminate\Http\Request;

class MasTrainingListController extends Controller
{
    private $filePath = 'images/training/';
    public function __construct()
    {
        $this->middleware('permission:training-application/training-lists,view')->only('index');
        $this->middleware('permission:training-application/training-lists,create')->only('store');
        $this->middleware('permission:training-application/training-lists,edit')->only('update');
        $this->middleware('permission:training-application/training-lists,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();

        $trainingLists = MasTrainingList::filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'));

        return view('training-application.training-lists.index', compact('privileges', 'trainingLists'));
    }

    public function create()
    {
        $trainingTypes = MasTrainingType::get(['id', 'name']);
        $trainingNatures = MasTrainingNature::get(['id', 'name']);
        $fundingTypes = MasTrainingFundingType::get(['id', 'name']);
        $country = MasCountry::get(['id', 'name']);
        $dzonkhag = MasDzongkhag::get(['id', 'dzongkhag']);
        $department = MasDepartment::where('status', 1)->get(['id', 'name']);
        $trainingExpenseTypes = MasTrainingExpenseType::get(['id', 'name']);
        return view('training-application.training-lists.create', compact('trainingTypes', 'fundingTypes', 'country', 'dzonkhag', 'department', 'trainingNatures', 'trainingExpenseTypes'));
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'type_id' => 'required|integer|exists:mas_training_types,id',
    //         'training_nature_id' => 'required|integer|exists:mas_training_natures,id',
    //         'funding_type_id' => 'required|integer|exists:mas_training_funding_types,id',
    //         'country_id' => 'nullable|integer|exists:mas_countries,id',
    //         'dzongkhag_id' => 'nullable|integer|exists:mas_dzongkhags,id',
    //         'location' => 'nullable|string|max:255',
    //         'institute' => 'required|string|max:255',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //         'department_id' => 'required|integer|exists:mas_departments,id',
    //         'amount_allotted' => 'nullable|numeric|min:0',
    //     ]);

    //     $training = new \App\Models\MasTrainingList();
    //     $training->title = $request->title;
    //     $training->type_id = $request->type_id;
    //     $training->training_nature_id = $request->training_nature_id;
    //     $training->funding_type_id = $request->funding_type_id;
    //     $training->country_id = $request->country_id;
    //     $training->dzongkhag_id = $request->dzongkhag_id;
    //     $training->location = $request->location;
    //     $training->institute = $request->institute;
    //     $training->start_date = $request->start_date;
    //     $training->end_date = $request->end_date;
    //     $training->department_id = $request->department_id;
    //     $training->amount_allocated = $request->amount_allotted;
    //     $training->created_by = auth()->user()->id;
    //     $training->save();

    //     return redirect()->route('training-application.training-lists.index')->with('success', 'Training List created successfully.');
    // }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // Training List validation
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
            'amount_allocated' => 'nullable|numeric|min:0',

            // Budget validation (array of expense types)
            'budget.*.training_expense_type_id' => 'required|integer|exists:mas_training_expense_types,id',
            'budget.*.amount_allocated' => 'required|numeric|min:0',
            'budget.*.by_company' => 'required|numeric|min:0',
            'budget.*.by_sponsor' => 'required|numeric|min:0',

            // Bond validation
            'bond.start_date' => 'required|date',
            'bond.end_date' => 'required|date|after_or_equal:bond.start_date',
            'bond.attachment.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);
        if ($request->type_id == 1) {
            $request->merge(['country_id' => 7]);
        }

        //  Create Training List
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
        $training->amount_allocated = $request->amount_allocated;
        $training->created_by = auth()->user()->id;
        $training->save();

        //  Store Budget Allocations
        if ($request->has('budget')) {
            foreach ($request->budget as $b) {
                \App\Models\TrainingBudgetAllocation::create([
                    'training_list_id' => $training->id,
                    'training_expense_type_id' => $b['training_expense_type_id'],
                    'amount_allocated' => $b['amount_allocated'],
                    'by_company' => $b['by_company'],
                    'by_sponsor' => $b['by_sponsor'],
                ]);
            }
        }

        //  Store Training Bond
        if ($request->has('bond')) {
            $attachments = [];

            if ($request->hasFile('bond.attachment')) {
                foreach ($request->file('bond.attachment') as $file) {
                    $path = uploadImageToDirectory($file, $this->filePath);

                    if (!empty($path)) {
                        $attachments[] = $path;
                    }
                }
            }

            \App\Models\TrainingBond::create([
                'training_list_id' => $training->id,
                'start_date'       => $request->bond['start_date'],
                'end_date'         => $request->bond['end_date'],
                'attachment'       => !empty($attachments) ? json_encode($attachments) : null,
            ]);
        }


        return redirect()->route('training-application.training-lists.index')
            ->with('success', 'Training List, Budget & Bond created successfully.');
    }



    public function edit($id)
    {
        $trainingList = MasTrainingList::with('budget', 'bond')->findOrFail($id);

        $trainingTypes = MasTrainingType::get(['id', 'name']);
        $trainingNatures = MasTrainingNature::get(['id', 'name']);
        $fundingTypes = MasTrainingFundingType::get(['id', 'name']);
        $countries = MasCountry::get(['id', 'name']);
        $dzongkhags = MasDzongkhag::get(['id', 'dzongkhag']);
        $departments = MasDepartment::where('status', 1)->get(['id', 'name']);
        $trainingExpenseTypes = MasTrainingExpenseType::get(['id', 'name']);

        return view('training-application.training-lists.edit', compact(
            'trainingList',
            'trainingTypes',
            'trainingNatures',
            'fundingTypes',
            'countries',
            'dzongkhags',
            'departments',
            'trainingExpenseTypes'
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

        return redirect()->route('training-application.training-lists.index')
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
