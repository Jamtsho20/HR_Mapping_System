<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasEmploymentType;

class EmploymentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/employment-types,view')->only('index');
        $this->middleware('permission:master/employment-types,create')->only('store');
        $this->middleware('permission:master/employment-types,edit')->only('update');
        $this->middleware('permission:master/employment-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employmentTypes = MasEmploymentType::filter($request)->orderBy('name')->paginate(30)->withQueryString();

        return view('masters.employment-types.index', compact('employmentTypes', 'privileges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employment_name' => 'required',
        ]);

        $employmentName = new MasEmploymentType;
        $employmentName->name = $request->employment_name;
        $employmentName->remarks = $request->remarks;
        $employmentName->save();

        return back()->with('msg_success', 'Employment type created successfully');
    }

    /**
     * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'employment_name' => 'required',
        ]);

        $employmentName = MasEmploymentType::findOrFail($id);
        $employmentName->name = $request->employment_name;
        $employmentName->remarks = $request->remarks;
        $employmentName->save();

        return back()->with('msg_success', 'Employment type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        try{
            MasEmploymentType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Employment type has been deleted');
        }catch(\Exception $e){
            return back()->with('msg_error', 'Employment type cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
