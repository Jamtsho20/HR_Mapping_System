<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasDzongkhag;
use App\Models\MasOffice;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/offices,view')->only('index');
        $this->middleware('permission:master/offices,create')->only('store');
        $this->middleware('permission:master/offices,edit')->only('update');
        $this->middleware('permission:master/offices,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $offices = MasOffice::with('dzongkhag')->filter($request)->paginate(10);
        $dzongkhags = MasDzongkhag::all();
        return view('masters.offices.index', compact('privileges', 'offices','dzongkhags'));
    }


    public function create()
    {
        $dzongkhags = MasDzongkhag::all();
        return view('masters.offices.create', compact('dzongkhags'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'dzongkhag' => 'required|exists:mas_dzongkhags,id',
            'status.is_active' => 'boolean',
        ]);

        // Create a new Office instance and fill it with the validated data
        $office = new MasOffice();
        $office->name = $validatedData['name'];
        $office->address = $validatedData['address'];
        $office->mas_dzongkhag_id = $validatedData['dzongkhag'];
        $office->status = $request->input('status.is_active', 0);

        // Save the Office instance to the database
        $office->save();

        // Redirect with success message
        return redirect()->route('offices.index')->with('success', 'Office created successfully.');
    }
    public function edit($id)
    {
        $office = MasOffice::findOrFail($id);
        $dzongkhags = MasDzongkhag::all();
        return view('masters.offices.edit', compact('office', 'dzongkhags'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'dzongkhag_id' => 'required|exists:mas_dzongkhags,id', // Ensure the dzongkhag exists in the database
            'status.is_active' => 'sometimes|boolean', // Validate the status checkbox
        ]);
    
        // Find the office by ID or throw a 404 error
        $office = MasOffice::findOrFail($id);
    
        // Update the office details
        $office->name = $request->input('name');
        $office->address = $request->input('address');
        $office->mas_dzongkhag_id = $request->input('dzongkhag_id');
    
        // Handle the status field (default to 0 if not set)
        $office->status = $request->input('status.is_active', 0);
    
        // Save the updated office
        $office->save();
    
        // Redirect with a success message
        return redirect('master/offices')->with('msg_success', 'Office updated successfully');
    }
    public function destroy(string $id)
    {
        try {
            MasOffice::findOrFail($id)->delete();
            return back()->with('msg_success', 'Region has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Region cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }
    
}
