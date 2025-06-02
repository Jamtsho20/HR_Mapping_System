<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasShiftType;
use Illuminate\Http\Request;

class MasShiftTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/shift-types,view')->only('index');
        $this->middleware('permission:master/shift-types,create')->only('store');
        $this->middleware('permission:master/shift-types,edit')->only('update');
        $this->middleware('permission:master/shift-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $shiftTypes = MasShiftType::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('masters.shift-types.index', compact('privileges', 'shiftTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('masters.shift-types.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $types = new \App\Models\MasShiftType();
        $types->name = $request->name;
        $types->start_time = $request->start_time;
        $types->end_time = $request->end_time;
        $types->save();

        return redirect('master/shift-types')->with('msg_success', 'Shift Types created successfully');
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
        $types = \App\Models\MasShiftType::findOrFail($id);
        return view('masters.shift-types.edit', compact('types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);


        $timing = MasShiftType::findOrFail($id);
        $timing->name = $request->name;
        $timing->start_time = $request->start_time;
        $timing->end_time = $request->end_time;

        $timing->save();
        return redirect('master/shift-types')->with('msg_success', 'Shift Types updated successfully');
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
            MasShiftType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Shift Types has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Shift Types cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
