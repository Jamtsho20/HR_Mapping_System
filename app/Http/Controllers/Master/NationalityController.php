<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasNationality;
use Illuminate\Http\Request;

class NationalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:master/nationalities,view')->only('index');
        $this->middleware('permission:master/nationalities,create')->only('store');
        $this->middleware('permission:master/nationalities,edit')->only('update');
        $this->middleware('permission:master/nationalities,delete')->only('destroy');
    }
    
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $nationalities = MasNationality::filter($request)->orderBy('name')->paginate(30);

        return view('masters.nationality.index', compact('nationalities', 'privileges'));
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

        $nationalities = new MasNationality();
        $nationalities->name = $request->name;
        $nationalities->save();

        return back()->with('msg_success', 'Nationality created successfully');
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

        $nationalities = MasNationality::findOrFail($id);
        $nationalities->name = $request->name;
        $nationalities->save();

        return back()->with('msg_success', 'Nationality updated successfully');
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
            MasNationality::findOrFail($id)->delete();

            return back()->with('msg_success', 'Nationality has been deleted');
        }catch(\Exception $e){
            return back()->with('msg_error', 'Nationality cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
