<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasEmploymentType;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/employment-types,view')->only('index');
        $this->middleware('permission:master/employment-types,create')->only('store');
        $this->middleware('permission:master/employment-types,edit')->only('update');
        $this->middleware('permission:master/employment-types,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employmentTypes = MasEmploymentType::filter($request)->orderBy('name')->paginate(config('global.pagination'))->withQueryString();
        return view('masters.employment-types.index', compact('employmentTypes', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.employment-types.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employment_name' => 'required',
        ]);

        $employmentName = new MasEmploymentType;
        $employmentName->name = $request->employment_name;
        $employmentName->remarks = $request->remarks;
        $employmentName->save();

        return redirect('master/employment-types')->with('msg_success', 'Employment type created successfully');;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employmentType = MasEmploymentType::findOrFail($id);
        return view('masters.employment-types.edit', compact('employmentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
        $request->validate([
            'name' => 'required',
        ]);

        $employmentType = MasEmploymentType::findOrFail($id);
        $employmentType->name = $request->name;
        $employmentType->remarks = $request->remarks;
        $employmentType->save();

  
        return redirect('master/employment-types')->with('msg_success', 'Employment type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            MasEmploymentType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Employment type has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Employment type cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
