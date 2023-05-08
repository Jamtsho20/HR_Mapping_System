<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasResignationType;
use Illuminate\Http\Request;

class ResignationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:master/resignation-types,view')->only('index');
        $this->middleware('permission:master/resignation-types,create')->only('store');
        $this->middleware('permission:master/resignation-types,edit')->only('update');
        $this->middleware('permission:master/resignation-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $resignationTypes = MasResignationType::filter($request)->orderBy('name')->paginate(30);

        return view('masters.resignation-types.index', compact('resignationTypes', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        return view('masters.resignation-types.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'resignation_type' => 'required',
        ]);

        $resignationTypes = new MasResignationType();
        $resignationTypes->name = $request->resignation_type;
        $resignationTypes->remarks = $request->remarks;
        $resignationTypes->save();

        return redirect('master/resignation-types')->with('msg_success', 'Resignation type created successfully');
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
        $resignation = MasResignationType::findOrFail($id);
        
        return view('masters.resignation-types.edit', compact('resignation'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'resignation_type' => 'required',
        ]);

        $resignationTypes = MasResignationType::findOrFail($id);
        $resignationTypes->name = $request->resignation_type;
        $resignationTypes->remarks = $request->remarks;
        $resignationTypes->save();

        return redirect('master/resignation-types')->with('msg_success', 'Resignation type updated successfully');
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
            MasResignationType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Resignation type has been deleted');
        }catch(\Exception $e){
            return back()->with('msg_error', 'Resignation type cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
