<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasDzongkhag;
use App\Models\MasRegion;
use App\Models\MasRegionLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/regions,view')->only('index');
        $this->middleware('permission:master/regions,create')->only('store');
        $this->middleware('permission:master/regions,edit')->only('update');
        $this->middleware('permission:master/regions,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $regions = MasRegion::filter($request)->orderBy('name')->paginate(30);
        return view('masters.region.index', compact('regions', 'privileges'));
    }

    public function create()
    {
        $dzongkhags = MasDzongkhag::all();
        return view('masters.region.create', compact('dzongkhags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:200',
            'rm_email' => 'nullable|email|max:200',
            'rm_phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        // try {
            $region = new MasRegion();
            $region->region_name = $request->region;
            $region->rm_email = $request->rm_email;
            $region->rm_phone = $request->rm_phone;
            $region->created_by = auth()->user()->id;
            $region->save();

            if (isset($request->details)) {
                $this->saveRegionLocation($request->details, $region->id);
            }

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('msg_error', 'The region could not be created, please try again.');
        

        return redirect('master/regions')->with('msg_success', 'Region created successfully');
    }

    public function edit(string $id)
    {
        $region = MasRegion::findOrFail($id);
        $regionLocations = $region->regionLocations()->paginate(10);
        $dzongkhags = MasDzongkhag::all();
        return view('masters.region.edit', compact('region', 'regionLocations', 'dzongkhags'));
    }

    public function update(Request $request, string $id)
    {
        // Check if the request is for updating the Region
        if ($request->has('region')) {
            $request->validate([
                'region' => 'required|string|max:200',
                'rm_email' => 'nullable|email|max:200',
                'rm_phone' => 'nullable|string|max:20',
            ]);

            $region = MasRegion::findOrFail($id);
            $region->region_name = $request->region;
            $region->rm_email = $request->rm_email;
            $region->rm_phone = $request->rm_phone;
            $region->edited_by = auth()->user()->id;
            $region->save();

            return redirect('master/regions')->with('msg_success', 'Region updated successfully');
        }

        // Add additional conditions for other forms if necessary
    }

    public function destroy(string $id)
    {
        try {
            MasRegion::findOrFail($id)->delete();
            return back()->with('msg_success', 'Region has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Region cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }

    private function saveRegionLocation($details, $regionId)
    {
        $regionLocationDetails = [];
        foreach ($details as $key => $value) {
            $regionLocationDetails[] = [
                'mas_region_id' => $regionId,
                'mas_dzongkhag_id' => $value['dzongkhag'], // Adjusted to use the correct field name
                'name' => $value['name'],
                'created_by' => auth()->user()->id,
            ];
        }

        MasRegionLocation::findOrFail($regionId)->regionLocationDetails()->createMany($regionLocationDetails);
    }
}
