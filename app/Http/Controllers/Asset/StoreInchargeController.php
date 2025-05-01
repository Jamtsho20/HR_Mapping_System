<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\AssetReturnApplication;
use App\Models\MasDzongkhag;
use App\Models\MasStore;
use Illuminate\Http\Request;

class StoreInchargeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permission:asset/store-incharge,view')->only('index');
        $this->middleware('permission:asset/store-incharge,create')->only('store');
        $this->middleware('permission:asset/store-incharge,edit')->only('update');
        $this->middleware('permission:asset/store-incharge,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance   ();
        $approvedApplications = AssetReturnApplication::with('details.store')->where('status', 3)->get();
        foreach ($approvedApplications as $application) {
            foreach ($application->details as $detail) {
                $storeId = $detail->store_id;
            }
        }
        return view('asset.store-incharge.index', compact('privileges','approvedApplications'));
    }

    public function show(string $id)
    {
        $return = AssetReturnApplication::with('details')->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\AssetReturnApplication::class, $return->id);
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
        $stores = MasStore::select('id', 'name')->get();

        return view('asset.store-incharge.show', compact('return', 'approvalDetail', 'dzongkhags', 'stores'));
    }
}
