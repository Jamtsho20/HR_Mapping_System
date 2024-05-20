<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasGewog;
use App\Models\MasDzongkhag;
use DB;

class GewogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/gewogs,view')->only('index');
        $this->middleware('permission:master/gewogs,create')->only('store');
        $this->middleware('permission:master/gewogs,edit')->only('update');
        $this->middleware('permission:master/gewogs,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $gewogs = MasGewog::filter($request)->orderBy('mas_dzongkhag_id')->with('dzongkhag')->paginate(30)->withQueryString();
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();

        return view('masters.gewog.index', compact('gewogs', 'privileges', 'dzongkhags'));
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

        $gewog = new MasGewog();
        $gewog->name = $request->name;
        $gewog->mas_dzongkhag_id = $request->mas_dzongkhag_id;
        $gewog->save();

        return back()->with('msg_success', 'Gewog created successfully');
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

        $gewog = MasGewog::findOrFail($id);
        $gewog->name = $request->name;
        $gewog->save();

        return redirect()->back()->with('msg_success', 'Gewog updated successfully');
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
            MasGewog::findOrFail($id)->delete();

            return back()->with('msg_success', 'Gewog has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Gewog cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
