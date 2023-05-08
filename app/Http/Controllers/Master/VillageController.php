<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasGewog;
use App\Models\MasVillage;
use App\Models\MasDzongkhag;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:master/villages,view')->only('index');
        $this->middleware('permission:master/villages,create')->only('store');
        $this->middleware('permission:master/villages,edit')->only('update');
        $this->middleware('permission:master/villages,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();

        $villages = MasVillage::filter($request)->orderBy('mas_gewog_id')->with('gewogs')->with('dzongkhag')->paginate(30)->withQueryString();
        $gewogs = MasGewog::select('id', 'name')->get();
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();

        return view('masters.village.index', compact('villages', 'privileges', 'gewogs','dzongkhags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
        $gewogs = MasGewog::select('id', 'name')->get();
        return view('masters.village.create',compact('dzongkhags','gewogs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'village' => 'required',
        ]);

        $village = new MasVillage();
        $village->village = $request->village;
        $village->mas_gewog_id = $request->mas_gewog_id;
        $village->save();

        return redirect('master/villages')->with('msg_success', 'Village created successfully');
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
        $village = MasVillage::findOrFail($id);

        $gewogs = MasGewog::select('id', 'name')->get();
        return view('masters.village.edit', compact( 'gewogs','village'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'village' => 'required',
        ]);

        $village = MasVillage::findOrFail($id);
        $village->village = $request->village;
        $village->mas_gewog_id = $request->mas_gewog_id;
        $village->save();

        return redirect('master/villages')->with('msg_success', 'Village updated successfully');
    }

    public function getGewog($id)
    {
        $gewogs = MasGewog::where('mas_dzongkhag_id', $id)->get();
        return $gewogs;
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
            MasVillage::findOrFail($id)->delete();

            return back()->with('msg_success', 'Village has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Village cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
