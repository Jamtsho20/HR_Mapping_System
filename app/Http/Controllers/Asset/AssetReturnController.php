<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasDzongkhag;
use App\Models\MasStore;
use App\Models\MasTransferType;
use Illuminate\Http\Request;

class AssetReturnController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/asset-return,view')->only('index');
        $this->middleware('permission:asset/asset-return,create')->only('store');
        $this->middleware('permission:asset/asset-return,edit')->only('update');
        $this->middleware('permission:asset/asset-return,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
      
        return view('asset.asset-return.index',compact('privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = MasTransferType::whereStatus(1)->get(['id', 'name']);
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
        $stores = MasStore::select('id', 'name')->get();

        return view('asset.asset-return.create', compact('types', 'dzongkhags','stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
