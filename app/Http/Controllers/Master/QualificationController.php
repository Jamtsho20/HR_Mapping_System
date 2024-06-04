<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasQualification;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:master/qualifications,view')->only('index');
        $this->middleware('permission:master/qualifications,create')->only('store');
        $this->middleware('permission:master/qualifications,edit')->only('update');
        $this->middleware('permission:master/qualifications,delete')->only('destroy');
    }
    public function create()
    {
        return view('masters.qualification.create');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $qualification = MasQualification::filter($request)->orderBy('name')->paginate(30);

        return view('masters.qualification.index', compact('qualification', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $qualification = new MasQualification();
        $qualification->name = $request->name;
        $qualification->save();

        return redirect('master/qualifications')->with('msg_success', 'Qualification created successfully');
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
        $qualification = MasQualification::findOrFail($id);
        return view('masters.qualification.edit', compact('qualification'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $qualification = MasQualification::findOrFail($id);
        $qualification->name = $request->name;
        $qualification->save();

        return redirect('master/qualifications')->with('msg_success', 'Qualification updated successfully');
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
            MasQualification::findOrFail($id)->delete();

            return back()->with('msg_success', 'Qualification has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Qualification cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
