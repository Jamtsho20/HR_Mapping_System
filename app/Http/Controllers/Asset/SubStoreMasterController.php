<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\SubStoreMaster;
use Illuminate\Http\Request;

class SubStoreMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:asset/sub-store-master,view')->only('index');
        $this->middleware('permission:asset/sub-store-master,create')->only('store');
        $this->middleware('permission:asset/sub-store-master,edit')->only('update');
        $this->middleware('permission:asset/sub-store-master,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $subStores = SubStoreMaster::filter($request)->orderBy('store_name')->paginate(30);
        return view('asset.sub-store-master.index', compact('subStores', 'privileges'));
    }
    
    public function create()
    {
        return view('asset.sub-store-master.create');

    }
    public function store(Request $request)
    {
    // Validate the request data
    $request->validate([
        'store_name' => 'required|string|max:150',
        'location' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
    ]);

    // Create a new SubStoreMaster instance and fill it with the validated data
    $subStore = new SubStoreMaster();
    $subStore->store_name = $request->store_name;
    $subStore->location = $request->location;
    $subStore->status = $request->status;
    $subStore->created_by = auth()->user()->id; // Track who created the record

    // Save the new SubStoreMaster instance to the database
    $subStore->save();

    // Redirect to the index page with a success message
    return redirect('asset/sub-store-master')->with('msg_success', 'Sub Store created successfully');
    }
    public function show(string $id)
    {
    //
    }

    public function edit(string $id)
    {
    $subStore = SubStoreMaster::findOrFail($id);
    return view('asset.sub-store-master.edit', compact('subStore'));
    }

    public function update(Request $request, string $id)
    {
    // Validate the incoming request data
    $request->validate([
        'store_name' => 'required|string|max:150',
        'location' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
    ]);

    // Find the existing sub-store by ID
    $subStore = SubStoreMaster::findOrFail($id);

    // Update the sub-store properties with the request data
    $subStore->store_name = $request->store_name;
    $subStore->location = $request->location;
    $subStore->status = $request->status;
    $subStore->updated_by = auth()->user()->id; // Track who edited the record

    // Save the updated model instance to the database
    $subStore->save();

    // Redirect to the sub-store listing page with a success message
    return redirect('asset/sub-store-master')->with('msg_success', 'Sub Store updated successfully');
    }

    public function destroy(string $id)
    {
    try {
        // Attempt to find and delete the sub-store
        SubStoreMaster::findOrFail($id)->delete();

        // Redirect back with a success message
        return back()->with('msg_success', 'Sub Store has been deleted');
    } catch (\Exception $e) {
        // Handle the exception, typically due to foreign key constraints
        return back()->with('msg_error', 'Sub Store cannot be deleted as it has been used by another module. For further information, contact the system admin.');
    }
    }







}
