<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Models\MasSifaType;
use App\Models\SifaDependent;
use App\Models\SifaDocument;
use App\Models\SifaNomination;
use App\Models\SifaRegistration;
use App\Models\User;
use App\Services\ApprovalService;
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

    protected $rules = [
        'employee_id' => 'required|exists:mas_employees,id', // Ensure an employee is selected
        'is_registered' => 'required|in:yes,no',
        'remark' => 'nullable|string',

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
        'cid_of_dep_nom.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Handle multiple files
        'marriage_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'family_tree_spouse' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'spouse_cid' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'adopted_children' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'if_divorced' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $sifaRegistration = SifaRegistration::where('mas_employee_id', auth()->id())->first();
        return view('sifa.sifa-registration.index', compact('privileges', 'sifaRegistration'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        return view('sifa.sifa-registration.create', compact('user'));
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     // Validate the incoming data
    //     $this->validate($request, $this->rules);

    //     try {
    //         DB::beginTransaction();

    //         // Create the Sifa Registration record
    //         $sifaRegistration = new SifaRegistration();
    //         $sifaRegistration->mas_employee_id = $request->employee_id; // Store employee ID
    //         $sifaRegistration->status = 1; // Set status to 1
    //         $sifaRegistration->save(); // Save the SifaRegistration record

    //         // Loop through and store the nominations
    //         foreach ($request->sifa_nomination as $nominationData) {
    //             $sifaNomination = new SifaNomination();
    //             $sifaNomination->sifa_registration_id = $sifaRegistration->id; // Link to the SifaRegistration
    //             $sifaNomination->nominee_name = $nominationData['nominee_name'];
    //             $sifaNomination->relation_with_employee = $nominationData['relation_with_employee'];
    //             $sifaNomination->cid_number = $nominationData['cid_number'];
    //             $sifaNomination->percentage_of_share = $nominationData['percentage_of_share'];
    //             $sifaNomination->save(); // Save nomination data
    //         }

    //         // Store SIFA Dependents Data
    //         foreach ($request->sifa_dependents as $dependentData) {
    //             $sifaDependent = new SifaDependent();
    //             $sifaDependent->sifa_registration_id = $sifaRegistration->id; // Link to the SifaRegistration
    //             $sifaDependent->dependent_name = $dependentData['dependent_name'];
    //             $sifaDependent->relation_with_employee = $dependentData['relation_with_employee'];
    //             $sifaDependent->cid_number = $dependentData['cid_number'];
    //             $sifaDependent->save();
    //         }

    //         //Store SIFA Documents Data
    //         $data = ['sifa_registration_id' => $sifaRegistration->id];

    //         // Loop through fields with single file uploads


    //         foreach (['family_tree', 'cid_of_dep_nom', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced'] as $field) {
    //             if ($request->hasFile($field)) {
    //                 if (is_array($request->file($field))) {
    //                     // Multiple files
    //                     $uploadedPaths = [];
    //                     foreach ($request->file($field) as $file) {
    //                         $uploadedPaths[] = uploadImageToDirectory($file, $this->filePath);
    //                     }
    //                     $data[$field] = $uploadedPaths; // Store array directly
    //                 } else {
    //                     // Single file
    //                     $file = $request->file($field);
    //                     $data[$field] = uploadImageToDirectory($file, $this->filePath);
    //                 }
    //             } else {
    //                 $data[$field] = null;
    //             }
    //         }
    //         SifaDocument::create($data);

    //         // Commit the transaction
    //         DB::commit();

    //         // Redirect to the index or confirmation page
    //         return redirect()->route('sifa-registration.index')->with('msg_success', 'Sifa Registration saved successfully!');
    //     } catch (\Exception $e) {
    //         // Rollback in case of error
    //         DB::rollBack();
    //         return back()->withInput()->with('msg_error', 'Error: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        // Conditionally apply validation rules
        $sifaTypeId = MasSifaType::first()->id;
        $rules = $this->rules;
        $conditionFields = approvalHeadConditionFields(SIFA_REGISTRATION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        //dd($conditionFields);
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($sifaTypeId, \App\Models\MasSifaType::class, $conditionFields ?? []);
        // If the user selects 'no', only validate remarks
        if ($request->is_registered == 'no') {
            $rules['remark'] = 'required|string'; // Make remark required
        } else {
            $this->validate($request, $rules);
        }
        try {
            DB::beginTransaction();
    
            // Create the Sifa Registration record
            $sifaRegistration = new SifaRegistration();
            $sifaType = MasSifaType::first();
            $sifaRegistration->mas_employee_id = $request->employee_id; // Store employee ID
            $sifaRegistration->status = 1; // Set status to 1
    
            // Check if the user wants to register for SIFA
            if ($request->is_registered == 'no') {
                // If "No", set is_registered to 0 and store remarks
                $sifaRegistration->sifa_type_id = $sifaTypeId;
                $sifaRegistration->is_registered = 0;
                $sifaRegistration->remark = $request->remark;
            } else {
                // If "Yes", set is_registered to 1 (default behavior)
                $sifaRegistration->sifa_type_id = $sifaTypeId;
                $sifaRegistration->is_registered = 1;
            }
    
            $sifaRegistration->save(); // Save the SifaRegistration record
    
            // If the user selects "Yes", process the nomination, dependents, and documents
            if ($request->is_registered == 'yes') {
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
    
                // Store SIFA Documents Data
                $data = ['sifa_registration_id' => $sifaRegistration->id];
    
                // Loop through fields with single file uploads
                foreach (['family_tree', 'cid_of_dep_nom', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced'] as $field) {
                    if ($request->hasFile($field)) {
                        if (is_array($request->file($field))) {
                            // Multiple files
                            $uploadedPaths = [];
                            foreach ($request->file($field) as $file) {
                                $uploadedPaths[] = uploadImageToDirectory($file, $this->filePath);
                            }
                            $data[$field] = $uploadedPaths; // Store array directly
                        } else {
                            // Single file
                            $file = $request->file($field);
                            $data[$field] = uploadImageToDirectory($file, $this->filePath);
                        }
                    } else {
                        $data[$field] = null;
                    }
                }
                SifaDocument::create($data); // Store document data
            }
            $sifaRegistration->histories()->create([
                'approval_option' => $approverByHierarchy['approval_option'],
                'hierarchy_id' => $approverByHierarchy['hierarchy_id'] ?? null,
                'level_id' => $approverByHierarchy['next_level']->id ?? null,
                'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'] ?? null,
                'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id ?? null,
                'level_sequence' => $approverByHierarchy['next_level']->sequence ?? null,
                'status' => $approverByHierarchy['application_status'],
                'remarks' => $request->remarks ?? null,
                'action_performed_by' => loggedInUser(),
            ]);
    
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
    public function show($id, Request $request)
    {
        $user = auth()->user();
        $sifaRegistration = SifaRegistration::with(['SifaNomination', 'SifaDependent', 'SifaDocument'])->findOrFail($id);
        $sifaDocuments = SifaDocument::where('sifa_registration_id', $id)->first();
        //dd($sifaRegistration->sifaDocument);

        return view('sifa.sifa-registration.show', compact('user', 'sifaRegistration', 'sifaDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        // Find the SifaRegistration by id
        $sifaRegistration = SifaRegistration::with(['SifaNomination', 'SifaDependent', 'SifaDocument'])->findOrFail($id);
        $sifaNominations = SifaNomination::all();
        $sifaDependents = SifaDependent::all();
        $sifaDocuments = SifaDocument::where('sifa_registration_id', $id)->first();

        // You can also pass other data, such as employee data, if needed
        return view('sifa.sifa-registration.edit', compact('sifaRegistration', 'user', 'sifaDocuments', 'sifaNominations', 'sifaDependents'));
    }
    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //      //dd($request->all());
    //     // Validate incoming data
    //     $validatedData = $request->validate([
    //         'employee_id' => 'required|exists:mas_employees,id', // Ensure an employee is selected
    //         'sifa_nomination' => 'required|array|min:1', // At least one nomination should be provided
    //         'sifa_nomination.*.id' => 'nullable|exists:sifa_nominations,id', // To track existing nominations
    //         'sifa_nomination.*.nominee_name' => 'required|string|max:255',
    //         'sifa_nomination.*.relation_with_employee' => 'required|string|max:255',
    //         'sifa_nomination.*.cid_number' => 'required|string|max:11',
    //         'sifa_nomination.*.percentage_of_share' => 'required|numeric|min:1|max:100',

    //         'sifa_dependents' => 'required|array|min:1',
    //         'sifa_dependents.*.id' => 'nullable|exists:sifa_dependents,id', // To track existing dependents
    //         'sifa_dependents.*.dependent_name' => 'required|string|max:255',
    //         'sifa_dependents.*.relation_with_employee' => 'required|string|max:255',
    //         'sifa_dependents.*.cid_number' => 'required|string|max:255',

    //         'family_tree' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'cid_of_dep_nom.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Handle multiple files
    //         'marriage_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'family_tree_spouse' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'spouse_cid' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'adopted_children' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         'if_divorced' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         // Find the SifaRegistration record
    //         $sifaRegistration = SifaRegistration::findOrFail($id);
    //         $sifaRegistration->mas_employee_id = $request->employee_id;
    //         $sifaRegistration->save();

    //         // Update or create nominations
    //         foreach ($request->sifa_nomination as $nominationData) {
    //             SifaNomination::updateOrCreate(
    //                 ['id' => $nominationData['id'] ?? null], // Check if nomination exists
    //                 [
    //                     'sifa_registration_id' => $sifaRegistration->id,
    //                     'nominee_name' => $nominationData['nominee_name'],
    //                     'relation_with_employee' => $nominationData['relation_with_employee'],
    //                     'cid_number' => $nominationData['cid_number'],
    //                     'percentage_of_share' => $nominationData['percentage_of_share'],
    //                 ]
    //             );
    //         }

    //         // Update or create dependents
    //         foreach ($request->sifa_dependents as $dependentData) {
    //             SifaDependent::updateOrCreate(
    //                 ['id' => $dependentData['id'] ?? null], // Check if dependent exists
    //                 [
    //                     'sifa_registration_id' => $sifaRegistration->id,
    //                     'dependent_name' => $dependentData['dependent_name'],
    //                     'relation_with_employee' => $dependentData['relation_with_employee'],
    //                     'cid_number' => $dependentData['cid_number'],
    //                 ]
    //             );
    //         }

    //         // Handle document uploads
    //         $data = ['sifa_registration_id' => $sifaRegistration->id];
    //         foreach (['family_tree', 'cid_of_dep_nom', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced'] as $field) {
    //             if ($request->hasFile($field)) {
    //                 if (is_array($request->file($field))) {
    //                     // Multiple files
    //                     $uploadedPaths = [];
    //                     foreach ($request->file($field) as $file) {
    //                         $uploadedPaths[] = uploadImageToDirectory($file, $this->filePath);
    //                     }
    //                     $data[$field] = $uploadedPaths;
    //                 } else {
    //                     // Single file
    //                     $file = $request->file($field);
    //                     $data[$field] = uploadImageToDirectory($file, $this->filePath);
    //                 }
    //             }
    //         }

    //         // Update or create the SifaDocument
    //         SifaDocument::updateOrCreate(
    //             ['sifa_registration_id' => $sifaRegistration->id],
    //             $data
    //         );

    //         DB::commit();

    //         return redirect()->route('sifa-registration.index')->with('msg_success', 'Sifa Registration updated successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withInput()->with('msg_error', 'Error: ' . $e->getMessage());
    //     }
    // }

    public function update(Request $request, string $id)
    {
        //dd($request->all());
        // Validate incoming data
        //$this->validate($request, $this->rules);

        try {
            DB::beginTransaction();

            // Find the SifaRegistration record
            $sifaRegistration = SifaRegistration::findOrFail($id);
            $sifaRegistration->mas_employee_id = $request->employee_id;
            $sifaRegistration->save();

            // Delete old nominations and dependents
            SifaNomination::where('sifa_registration_id', $sifaRegistration->id)->delete();
            SifaDependent::where('sifa_registration_id', $sifaRegistration->id)->delete();

            // Update or create new nominations
            foreach ($request->sifa_nomination as $nominationData) {
                SifaNomination::updateOrCreate(
                    ['id' => $nominationData['id'] ?? null], // Check if nomination exists
                    [
                        'sifa_registration_id' => $sifaRegistration->id,
                        'nominee_name' => $nominationData['nominee_name'],
                        'relation_with_employee' => $nominationData['relation_with_employee'],
                        'cid_number' => $nominationData['cid_number'],
                        'percentage_of_share' => $nominationData['percentage_of_share'],
                    ]
                );
            }

            // Update or create new dependents
            foreach ($request->sifa_dependents as $dependentData) {
                SifaDependent::updateOrCreate(
                    ['id' => $dependentData['id'] ?? null], // Check if dependent exists
                    [
                        'sifa_registration_id' => $sifaRegistration->id,
                        'dependent_name' => $dependentData['dependent_name'],
                        'relation_with_employee' => $dependentData['relation_with_employee'],
                        'cid_number' => $dependentData['cid_number'],
                    ]
                );
            }

            // Handle document uploads
            $data = ['sifa_registration_id' => $sifaRegistration->id];
            foreach (['family_tree', 'cid_of_dep_nom', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced'] as $field) {
                if ($request->hasFile($field)) {
                    if (is_array($request->file($field))) {
                        // Multiple files
                        $uploadedPaths = [];
                        foreach ($request->file($field) as $file) {
                            $uploadedPaths[] = uploadImageToDirectory($file, $this->filePath);
                        }
                        $data[$field] = $uploadedPaths;
                    } else {
                        // Single file
                        $file = $request->file($field);
                        $data[$field] = uploadImageToDirectory($file, $this->filePath);
                    }
                }
            }

            // Update or create the SifaDocument
            SifaDocument::updateOrCreate(
                ['sifa_registration_id' => $sifaRegistration->id],
                $data
            );

            DB::commit();

            return redirect()->route('sifa-registration.index')->with('msg_success', 'Sifa Registration updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', 'Error: ' . $e->getMessage());
        }
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
