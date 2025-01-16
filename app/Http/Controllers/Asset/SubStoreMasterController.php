<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubStoreMasterController extends Controller
{
    private $rules = [
        'name' => 'required',
        'location' => 'required',
        'sub_stores.*.name' => 'required',
    ];

    private $messages = [
        'name.required' => 'Main store name is required.',
        'location.required' => 'Location is required.',
        'sub_stores.*.name.required' => 'Sub store name is required.',
    ];
    public function __construct()
    {
        $this->middleware('permission:asset/mas-store,view')->only('index');
        $this->middleware('permission:asset/mas-store,create')->only('store');
        $this->middleware('permission:asset/mas-store,edit')->only('update');
        $this->middleware('permission:asset/mas-store,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $mainStores = MasStore::filter($request)->orderBy('name')->paginate(10); // Ensure this is correct
        return view('asset.mas-store.index', compact('mainStores', 'privileges')); // Pass $mainStores to the view
    }


    public function create()
    {
        return view('asset.mas-store.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);

        DB::transaction(function () use ($request) {
            $store = new MasStore;
            $store->name = $request->name;
            $store->location = $request->location;
            $store->status = $request->input('status.is_active', 0); // This remains unchanged
            $store->save();

            $subStores = [];
            foreach ($request->sub_stores as $key => $value) {
                $subStores[] = [
                    'name' => $value['name'],
                    'location' => $value['location'],
                    'status' => $value['status'] === 'active' ? 1 : 0, // Convert to integer
                    'mas_store_id' => $store->id,
                ];
            }

            $store->subStores()->createMany($subStores);
        });

        return redirect()->route('mas-store.index')->with('msg_success', 'Store and Sub-Stores created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $store = MasStore::findOrFail($id);
        return view('asset.mas-store.edit', compact('store'));
    }

    public function update(Request $request, $id)
{
    // Updating main store
    $store = MasStore::findOrFail($id);
    $store->name = $request->name;
    $store->location = $request->location;
    $store->status = $request->input('status.is_active', 0);
    $store->save();

    // Sub-stores updating logic
    foreach ($request->sub_stores as $key => $value) {
        $subStore = $store->subStores()->findOrFail($key); // Use $key here
        $subStore->name = $value['name'];
        $subStore->location = $value['location'];
        $subStore->status = $value['status'] == '1' ? 1 : 0; // Convert to integer
        $subStore->save();
    }

    return redirect()->route('mas-store.index')->with('msg_success', 'Store and Sub-Stores updated successfully.');
}


    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the sub-store
            MasStore::findOrFail($id)->delete();

            // Redirect back with a success message
            return back()->with('msg_success', 'Sub Store has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Sub Store cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }
}
