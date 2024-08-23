<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasDzongkhag;
use App\Models\MasRegion;
use App\Models\MasRegionLocation;
use Illuminate\Http\Request;

class RegionLocationController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:region/region-location,view')->only('index');
        // $this->middleware('permission:region/region-location,create')->only('store');
        // $this->middleware('permission:region/region-location,edit')->only('update');
        // $this->middleware('permission:region/region-location,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $regionLocationDetails = MasRegionLocation::with(['region', 'dzongkhag']) // load both relationships
            ->filter($request)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('region.region-location.index', compact('regionLocationDetails', 'privileges'));
    }

    public function create(Request $request)
    {
        $dzongkhags = MasDzongkhag::all(); 
        $regionLocId = $request->regionlocationId;
        $regionLocation = MasRegion::whereId($regionLocId)->first(); 
        return view('masters.region-location.create', compact('regionLocation','dzongkhags'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'mas_region_id' => 'required|exists:mas_regions,id', 
            'region_name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'dzongkhag' => 'required|exists:mas_dzongkhags,id',
        ]);

        // Create a new RegionLocation instance and save it to the database
        $regionLocation = new MasRegionLocation();
        $regionLocation->mas_region_id = $request->mas_region_id;
        $regionLocation->region_name = $request->region_name;
        $regionLocation->region = $request->region;
        $regionLocation->mas_dzongkhag_id = $request->dzongkhag;  
        $regionLocation->save();

        // Redirect to the index page with a success message
        return redirect()->route('region-location.index')->with('msg_success', 'Region location created successfully');
    }
    
}
