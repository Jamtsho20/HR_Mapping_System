<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasVehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/vehicles,view')->only('index');
        $this->middleware('permission:master/vehicles,create')->only('store');
        $this->middleware('permission:master/vehicles,edit')->only('update');
        $this->middleware('permission:master/vehicles,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $vehicles = MasVehicle::filter($request)->paginate(10);
        return view('masters.vehicles.index', compact('privileges', 'vehicles'));
    }


    public function create()
    {
        return view('masters.vehicles.create');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_no' => 'required|string|max:255|unique:mas_vehicles,vehicle_no',
            'vehicle_type' => 'required|in:1,2,3,4', // Light, Medium, Heavy, Two Wheeler
            'status.is_active' => 'boolean',
        ]);

        // Create a new MasVehicle instance and fill it with the validated data
        $vehicle = new MasVehicle();
        $vehicle->name = $validatedData['name'];
        $vehicle->vehicle_no = $validatedData['vehicle_no'];
        $vehicle->vehicle_type = $validatedData['vehicle_type'];
        $vehicle->is_active = $request->input('status.is_active', 0); // Updated to use 'is_active'

        // Save the MasVehicle instance to the database
        $vehicle->save();

        // Redirect with success message
        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }
    public function edit($id)
    {
        $vehicle = MasVehicle::findOrFail($id);

        return view('masters.vehicles.edit', compact('vehicle'));
    }
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_no' => 'required|string|max:255|unique:mas_vehicles,vehicle_no,' . $id, // Exclude the current vehicle from unique validation
            'vehicle_type' => 'required|in:1,2,3,4', // Light, Medium, Heavy, Two Wheeler
            'status.is_active' => 'boolean',
        ]);

        // Find the vehicle by ID or throw a 404 error
        $vehicle = MasVehicle::findOrFail($id);

        // Update the vehicle details with the validated data
        $vehicle->name = $validatedData['name'];
        $vehicle->vehicle_no = $validatedData['vehicle_no'];
        $vehicle->vehicle_type = $validatedData['vehicle_type'];
        $vehicle->is_active = $request->input('status.is_active', 0); // Set to 0 if not checked

        // Save the updated vehicle to the database
        $vehicle->save();

        // Redirect with success message
        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }


    public function destroy(string $id)
    {
        try {
            MasVehicle::findOrFail($id)->delete();
            return back()->with('msg_success', 'Vehicle has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Vehicle cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }
}
