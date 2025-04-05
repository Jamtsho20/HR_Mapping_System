<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $vehicleNos = MasVehicle::distinct()->pluck('vehicle_no');
        $vehicles = MasVehicle::filter($request)
            ->with(['vehicleType', 'department'])
            ->orderBy('created_at', 'asc')->paginate(30);
        return view('masters.vehicles.index', compact('privileges', 'vehicles', 'vehicleNos'));
    }


    public function create()
    {
        $vehicleTypes = DB::table('mas_vehicle_types')->pluck('name', 'id');
        $departments = DB::table('mas_departments')->pluck('name', 'id');
        return view('masters.vehicles.create', compact('vehicleTypes', 'departments'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'vehicle_no' => 'required|string|max:255|unique:mas_vehicles,vehicle_no',
            'vehicle_type' => 'required|exists:mas_vehicle_types,id',
            'department_id' => 'required|exists:mas_departments,id',
            'location' => 'required|string|max:255',
            'final_reading' => 'required|numeric',
            'status.is_active' => 'nullable|boolean',
        ]);

        try {
            // Create and save the vehicle
            $vehicle = new MasVehicle();
            $vehicle->vehicle_no = $validatedData['vehicle_no'];
            $vehicle->vehicle_type_id = $validatedData['vehicle_type']; // Store vehicle type ID
            $vehicle->department_id = $validatedData['department_id']; // Store department ID
            $vehicle->location = $validatedData['location'];
            $vehicle->final_reading = $validatedData['final_reading'];
            $vehicle->is_active = $request->has('status.is_active') ? 1 : 0; // Handle checkbox

            $vehicle->save();

            return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $vehicle = MasVehicle::findOrFail($id);
        $vehicleTypes = DB::table('mas_vehicle_types')->pluck('name', 'id');
        $departments = DB::table('mas_departments')->pluck('name', 'id');
        return view('masters.vehicles.edit', compact('vehicle', 'vehicleTypes', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = MasVehicle::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'vehicle_no' => 'required|string|max:255|unique:mas_vehicles,vehicle_no,' . $vehicle->id,
            'vehicle_type' => 'required|exists:mas_vehicle_types,id',
            'location' => 'required|string|max:255',
            'final_reading' => 'required|string|max:255',
            'department_id' => 'required|exists:mas_departments,id',
            'status.is_active' => 'boolean',
        ]);

        // Update the vehicle's data
        $vehicle->vehicle_no = $validatedData['vehicle_no'];
        $vehicle->vehicle_type_id = $validatedData['vehicle_type'];
        $vehicle->location = $validatedData['location'];
        $vehicle->final_reading = $validatedData['final_reading'];
        $vehicle->department_id = $validatedData['department_id'];
        $vehicle->is_active = $request->input('status.is_active', 0); // Default to 0 if not checked

        // Save the updated vehicle information
        $vehicle->save();

        // Redirect to the vehicles index with a success message
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
