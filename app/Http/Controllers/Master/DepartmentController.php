<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasDepartment;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/departments,view')->only('index');
        $this->middleware('permission:master/departments,create')->only('store');
        $this->middleware('permission:master/departments,edit')->only('update');
        $this->middleware('permission:master/departments,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $departments = MasDepartment::filter($request)->orderBy('name')->paginate(30);

        return view('masters.department.index', compact('departments', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('masters.department.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'short_name' => 'required',
            'name' => 'required',
        ]);

        $department = new MasDepartment;
        $department->short_name = $request->short_name;
        $department->name = $request->name;
        $department->save();

        return redirect('master/departments')->with('msg_success', 'Department created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(string $id)
    {
        $department = MasDepartment::findOrFail($id);
        return view('masters.department.edit', compact('department'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'short_name' => 'required',
            'name' => 'required',
        ]);

        $department = MasDepartment::findOrFail($id);
        $department->short_name = $request->short_name;
        $department->name = $request->name;
        $department->save();

        return redirect('master/departments')->with('msg_success', 'Department updated successfully');
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
            MasDepartment::findOrFail($id)->delete();

            return back()->with('msg_success', 'Department has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Department cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
