<?php

namespace App\Http\Controllers\Master;


use App\Http\Controllers\Controller;
use App\Models\MasDzongkhag;
use App\Models\MasRegion;
use App\Models\MasRegionLocation;
use Illuminate\Http\Request;

class RegionLocationController extends Controller
{
    protected $rules = [
        'mas_region_id' => 'required|exists:mas_regions,id', // Region ID must be a valid ID
        'name' => 'required', // Ensure the region name is provided
        'mas_dzongkhag_id' => 'required|exists:mas_dzongkhags,id', // Dzongkhag must be a valid ID
    ];
    
    protected $messages = [
        'mas_dzongkhag_id.required' => 'The Dzongkhag field is required.'
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $regionLocations = MasRegionLocation::with(['region', 'dzongkhag']) // Load both relationships
            ->filter($request) // If you have a filter method
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        $region = MasRegion::first(); // Adjust this as needed

        return view('masters.region-location.index', compact('regionLocations', 'privileges', 'region'));
    }


    public function create(Request $request)
    {
        //
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        MasRegionLocation::create([
            'mas_region_id' => $request->mas_region_id,
            'name' => $request->name,
            'mas_dzongkhag_id' => $request->mas_dzongkhag_id, // Correctly map to the foreign key
        ]);

        return redirect()->back()->with('msg_success', 'Region location created successfully.');
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, $this->rules, $this->messages);
    
        $regionLocation = MasRegionLocation::findOrFail($id);
        $regionLocation->name = $request->name;
        $regionLocation->mas_dzongkhag_id = $request->mas_dzongkhag_id;
        
        return redirect()->back()->with('msg_success', 'Region location updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            MasRegionLocation::findOrFail($id)->delete();
            return back()->with('msg_success', 'Region location has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Region location cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }
}
