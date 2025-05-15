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

            $privileges = $request->instance();
            $inchargeId = auth()->user()->id;
            $toBeReturned = AssetReturnApplication::where('status', 3)->where('received_acknowledged', 0)->whereHas('details', function ($query) use ($inchargeId) {
                $query->whereHas('store', function ($query) use ($inchargeId) {
                    $query->where('store_incharge', $inchargeId);
                });})->get();
            $returned = AssetReturnApplication::where('received_acknowledged', 1)->whereHas('details', function ($query) use ($inchargeId) {
                $query->whereHas('store', function ($query) use ($inchargeId) {
                    $query->where('store_incharge', $inchargeId);
                });})->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
         
            // $userAssets = $assets->concat($assetTransfer);

        return view('asset.store-incharge.index', compact('privileges','toBeReturned', 'returned'));
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
