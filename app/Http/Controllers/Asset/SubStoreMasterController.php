<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\SubStoreMaster;
use Illuminate\Http\Request;

class SubStoreMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:asset/sub-store-master,view')->only('index');
        $this->middleware('permission:asset/sub-store-master,create')->only('store');
        $this->middleware('permission:asset/sub-store-master,edit')->only('update');
        $this->middleware('permission:asset/sub-store-master,delete')->only('destroy');
    }

    public function index( Request $request)
    {
        $privileges = $request->instance();
        $substores= SubStoreMaster::filter($request)->orderBy('store_name')->paginate(30);

        return view('asset.sub-store-master.index', compact('substores', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('asset.sub-store-master.create');
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required',
            'location' => 'required',
            'status' => 'required',
        ]);
    
        // Create a new SubStoreMaster instance and save it
        $subStoreMaster = new SubStoreMaster();
        $subStoreMaster->store_name = $request->store_name;
        $subStoreMaster->location = $request->location;
        $subStoreMaster->status = $request->status;
      
        $subStoreMaster->save();
    
        return redirect()->route('asset.sub-store-master.index')->with('msg_success', 'SubStoreMaster created successfully');
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $substore = SubStoreMaster::findOrFail($id);
        return view('asset.sub-store-master.edit', compact('substore'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'store_name' => 'required',
        'location' => 'required',
        'status' => 'required',
    ]);

    $substore = SubStoreMaster::findOrFail($id);
    $substore->store_name = $request->store_name;
    $substore->location = $request->location;
    $substore->status = $request->status;
    $substore->save();

    return redirect()->route('asset.sub-store-master.index')->with('msg_success', 'SubStoreMaster updated successfully');
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
