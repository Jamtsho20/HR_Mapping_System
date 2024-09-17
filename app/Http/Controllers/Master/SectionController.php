<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use Illuminate\Http\Request;
use App\Models\MasSection;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/section,view')->only('index');
        $this->middleware('permission:master/section,create')->only('store');
        $this->middleware('permission:master/section,edit')->only('update');
        $this->middleware('permission:master/section,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $sections = MasSection::filter($request)->with('department')->paginate('10')->withQueryString();
        $departments = MasDepartment::select('id', 'short_name', 'name')->get();
        return view('masters.section.index', compact('sections', 'privileges','departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = MasDepartment::select('id', 'short_name', 'name')->get();
        return view('masters.section.create', compact( 'departments'));
    }

    public function edit(string $id)
    {
        $section = MasSection::findOrFail($id);
        $departments = MasDepartment::select('id', 'short_name', 'name')->get();
        return view('masters.section.edit', compact('section', 'departments'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mas_department_id' => 'required',
        ]);

        $section = new MasSection;
        $section->name = $request->name;
        $section->mas_department_id = $request->mas_department_id;
        $section->mas_employee_id = $request->mas_employee_id;
        $section->save();

        return redirect('master/section')->with('msg_success', 'Section created successfully');
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
            'name' => 'required',
        ]);

        $section = MasSection::findOrFail($id);
        $section->name = $request->name;
        $section->mas_department_id = $request->mas_department_id;
        $section->mas_employee_id = $request->mas_employee_id;
        $section->save();

        return redirect('master/section')->with('msg_success', 'Section updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            MasSection::findOrFail($id)->delete();

            return back()->with('msg_success', 'Section has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Section cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }

}
