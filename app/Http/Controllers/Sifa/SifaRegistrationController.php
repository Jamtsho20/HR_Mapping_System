<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Models\SifaDependent;
use App\Models\SifaDocument;
use App\Models\SifaNomination;
use App\Models\SifaRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SifaRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:sifa/sifa-registration,view')->only('index');
        $this->middleware('permission:sifa/sifa-registration,create')->only('store');
        $this->middleware('permission:sifa/sifa-registration,edit')->only('update');
        $this->middleware('permission:sifa/sifa-registration,delete')->only('destroy');
    }
    private $filePath = 'images/sifa/';

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $sifaRegistrations = SifaRegistration::with('employee')->get();
        return view('sifa.sifa-registration.index', compact('privileges','sifaRegistrations'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = User::all();  // Fetch all employees for the dropdown
        $selectedEmployee = null;

        if ($request->has('employee_id')) {
            $selectedEmployee = User::find($request->input('employee_id'));
        }

        return view('sifa.sifa-registration.create', compact('employees', 'selectedEmployee'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming data
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:mas_employees,id', // Ensure an employee is selected
            'sifa_nomination' => 'required|array|min:1', // At least one nomination should be provided
            'sifa_nomination.*.nominee_name' => 'required|string|max:255',
            'sifa_nomination.*.relation_with_employee' => 'required|string|max:255',
            'sifa_nomination.*.cid_number' => 'required|string|max:11',
            'sifa_nomination.*.percentage_of_share' => 'required|numeric|min:1|max:100',

            'sifa_dependents' => 'required|array|min:1',
            'sifa_dependents.*.dependent_name' => 'required|string|max:255',
            'sifa_dependents.*.relation_with_employee' => 'required|string|max:255',
            'sifa_dependents.*.cid_number' => 'required|string|max:255',

            'family_tree' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cid_of_dep_nom' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'marriage_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'family_tree_spouse' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'spouse_cid' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'adopted_children' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'if_divorced' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',


        ]);

        // Start the transaction to ensure atomic operation
        try {
            DB::beginTransaction();

            // Create the Sifa Registration record
            $sifaRegistration = new SifaRegistration();
            $sifaRegistration->mas_employee_id = $request->employee_id; // Store employee ID
            $sifaRegistration->status = 1; // Set status to 1
            $sifaRegistration->save(); // Save the SifaRegistration record

            // Loop through and store the nominations
            foreach ($request->sifa_nomination as $nominationData) {
                $sifaNomination = new SifaNomination();
                $sifaNomination->sifa_registration_id = $sifaRegistration->id; // Link to the SifaRegistration
                $sifaNomination->nominee_name = $nominationData['nominee_name'];
                $sifaNomination->relation_with_employee = $nominationData['relation_with_employee'];
                $sifaNomination->cid_number = $nominationData['cid_number'];
                $sifaNomination->percentage_of_share = $nominationData['percentage_of_share'];
                $sifaNomination->save(); // Save nomination data
            }

            // Store SIFA Dependents Data
            foreach ($request->sifa_dependents as $dependentData) {
                $sifaDependent = new SifaDependent();
                $sifaDependent->sifa_registration_id = $sifaRegistration->id; // Link to the SifaRegistration
                $sifaDependent->dependent_name = $dependentData['dependent_name'];
                $sifaDependent->relation_with_employee = $dependentData['relation_with_employee'];
                $sifaDependent->cid_number = $dependentData['cid_number'];
                $sifaDependent->save();
            }

            //Store SIFA Documents Data
            $data = ['sifa_registration_id' => $sifaRegistration->id];

            foreach (['family_tree', 'cid_of_dep_nom', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced'] as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $data[$field] = uploadImageToDirectory($file, $this->filePath); // Store the file path for each field
                }
            }
            SifaDocument::create($data);

            // Commit the transaction
            DB::commit();

            // Redirect to the index or confirmation page
            return redirect()->route('sifa-registration.index')->with('msg_success', 'Sifa Registration saved successfully!');
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
            return back()->withInput()->with('msg_error', 'Error: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { {
            try {
                // Attempt to find and delete the Sifa Registration
                SifaRegistration::findOrFail($id)->delete();
                // Redirect back with a success message
                return back()->with('msg_success', 'Sifa Registration has been deleted');
            } catch (\Exception $e) {
                // Handle the exception, typically due to foreign key constraints
                return back()->with('msg_error', 'Sifa Registration cannot be deleted as it has been used by another module. For further information, contact the system admin.');
            }
        }
    }
}
