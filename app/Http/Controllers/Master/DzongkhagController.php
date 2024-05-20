<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasDzongkhag;

class DzongkhagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/dzongkhags,view')->only('index');
        $this->middleware('permission:master/dzongkhags,create')->only('store');
        $this->middleware('permission:master/dzongkhags,edit')->only('update');
        $this->middleware('permission:master/dzongkhags,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $dzongkhags = MasDzongkhag::filter($request)->orderBy('dzongkhag')->paginate(30);

        return view('masters.dzongkhag.index', compact('dzongkhags', 'privileges'));
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
            'dzongkhag' => 'required',
        ]);

        $dzongkhag = new MasDzongkhag;
        $dzongkhag->dzongkhag = $request->dzongkhag;
        $dzongkhag->save();

        return back()->with('msg_success', 'Dzongkhag created successfully');
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
            'dzongkhag' => 'required',
        ]);

        $dzongkhag = MasDzongkhag::findOrFail($id);
        $dzongkhag->dzongkhag = $request->dzongkhag;
        $dzongkhag->save();

        return back()->with('msg_success', 'Dzongkhag updated successfully');
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
            MasDzongkhag::findOrFail($id)->delete();

            return back()->with('msg_success', 'Dzongkhag has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Dzongkhag cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
