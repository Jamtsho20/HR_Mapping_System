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
        $regions = MasRegion::with('user')->filter($request)->orderBy('name')->paginate(config('global.pagination'));
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
            'details.*.name' => 'required',
            'details.*.mas_dzongkhag_id' => 'required' 
        ]);

        DB::beginTransaction();
        try {
            $region = new MasRegion();
            $region->region_name = $request->region;
            $region->mas_employee_id = $request->mas_employee_id;
            $region->status = $request->input('status.is_active', 0);
            $region->save();

            if (isset($request->details)) {
                $this->saveRegionLocation($request->details, $region->id);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('msg_error', 'The region could not be created, please try again.');
        }

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
        // Validate the incoming request data
        $request->validate([
            'region' => 'required|string|max:200',
            'mas_employee_id' => 'required|integer|exists:mas_employees,id',
            'status.is_active' => 'sometimes|boolean', // Validate the status checkbox
        ]);

        // Find the region by ID or throw a 404 error
        $region = MasRegion::findOrFail($id);

        // Update the region details
        $region->region_name = $request->region;
        $region->mas_employee_id = $request->mas_employee_id;

        // Handle the status field (default to 0 if not set)
        $region->status = $request->input('status.is_active', 0);

        // Save the updated region
        $region->save();

        // Redirect with a success message
        return redirect('master/regions')->with('msg_success', 'Region updated successfully');
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
                'mas_dzongkhag_id' => $value['mas_dzongkhag_id'],
                'name' => $value['name'],
                'created_by' => auth()->id(),
            ];
        }

        // Use the MasRegionLocation model to create many records
        MasRegionLocation::insert($regionLocationDetails);
    }
}
