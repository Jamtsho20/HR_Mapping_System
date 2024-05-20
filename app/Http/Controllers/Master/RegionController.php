<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasRegion;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:master/regions,view')->only('index');
        $this->middleware('permission:master/regions,create')->only('store');
        $this->middleware('permission:master/regions,edit')->only('update');
        $this->middleware('permission:master/regions,delete')->only('destroy');
    }

    public function index( Request $request)
    {
        $privileges = $request->instance();
        $regions= MasRegion::filter($request)->orderBy('region_name')->paginate(30);

        return view('masters.region.index', compact('regions', 'privileges'));
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
            'region' => 'required',
        ]);

        $region = new MasRegion();
        $region->region_name = $request->region;
        $region->save();

        return back()->with('msg_success', 'Region created successfully');
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
            'region' => 'required',
        ]);

        $region = MasRegion::findOrFail($id);
        $region->region_name = $request->region;
        $region->save();

        return back()->with('msg_success', 'Region updated successfully');
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
            MasRegion::findOrFail($id)->delete();

            return back()->with('msg_success', 'Region has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Region cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
