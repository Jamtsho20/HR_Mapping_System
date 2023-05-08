<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasDesignation;

class DesignationController extends Controller

{
    public function __construct()
    {
        $this->middleware('permission:master/designations,view')->only('index');
        $this->middleware('permission:master/designations,create')->only('store');
        $this->middleware('permission:master/designations,edit')->only('update');
        $this->middleware('permission:master/designations,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $designations = MasDesignation::filter($request)->orderBy('name')->paginate(30);

        return view('masters.designation.index', compact('designations', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.designation.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $designation = new MasDesignation;
        $designation->name = $request->name;
        $designation->save();

        return redirect('master/designations')->with('msg_success', 'Designation created successfully');
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
        $designation = MasDesignation::findOrFail($id);
        return view('masters.designation.edit', compact('designation'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $designation = MasDesignation::findOrFail($id);
        $designation->name = $request->name;
        $designation->save();

        return redirect('master/designations')->with('msg_success', 'Designation updated successfully');
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
            MasDesignation::findOrFail($id)->delete();

            return back()->with('msg_success', 'Designation has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Designation cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
