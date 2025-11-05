<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use App\Models\MasAssets;
use Illuminate\Http\Request;

class MyAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:my-profile/my-asset,view')->only('index');
        $this->middleware('permission:my-profile/my-asset,create')->only('store');
        $this->middleware('permission:my-profile/my-asset,edit')->only('update');
        $this->middleware('permission:my-profile/my-asset,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $assetData = $this->getAssetData();
        return view('my-profile.my-asset.index', compact('privileges','assetData'));
    }
    private function getAssetData()
    {
        $empID = auth()->user()->id;
         $assets = MasAssets::where('current_employee_id', $empID)->where('is_transfered', 0)->where('is_returned', 0)->where('asset_type', 1)->with('receivedSerial.requisitionDetail.grnItemDetail.item')->get();

        return $assets;
    }
}
