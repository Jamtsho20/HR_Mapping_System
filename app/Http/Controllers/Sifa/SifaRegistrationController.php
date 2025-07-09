<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Models\MasSifaType;
use App\Models\SifaDependent;
use App\Models\SifaDocument;
use App\Models\SifaNomination;
use App\Models\SifaRegistration;
use App\Models\SifaRetirementAndNomination;
use App\Models\User;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        'remarks' => 'nullable|string',

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

    public function store(Request $request)
    {

        // Conditionally apply validation rules
        $sifaTypeId = MasSifaType::first()->id;
        $rules = $this->rules;

        $conditionFields = approvalHeadConditionFields(SIFA_REGISTRATION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($sifaTypeId, \App\Models\MasSifaType::class, $conditionFields ?? []);
        // If the user selects 'no', only validate remarkss
        if ($request->is_registered == 'no') {
            $rules['remarks'] = 'required|string';
            // Validate the nominations array
            $rules['sifa_retirement_and_nomination'] = 'required|array|min:1'; // Ensure at least one nominee is added

            // Loop through each nomination entry for dynamic validation
            foreach ($request->sifa_retirement_and_nomination as $key => $value) {
                $rules["sifa_retirement_and_nomination.$key.nominee_name"] = 'required|string|max:255';
                $rules["sifa_retirement_and_nomination.$key.relation_with_employee"] = 'required|string|max:255';
                $rules["sifa_retirement_and_nomination.$key.cid_number"] = 'required|digits:11'; // Assuming CID is 11 digits
                $rules["sifa_retirement_and_nomination.$key.percentage_of_share"] = 'required|numeric|min:1|max:100';
            }
        } else {
            $this->validate($request, $rules);
        }
        try {
            DB::beginTransaction();

            $sifaRegistration = new SifaRegistration();
            $sifaType = MasSifaType::first(); // Assuming this gets the correct Sifa Type
            $sifaRegistration->mas_employee_id = $request->employee_id; // Store employee ID
            $sifaRegistration->status = 1; // Set status to 1
            $sifaRegistration->sifa_type_id = $sifaType->id; // Assuming you have the correct type ID
            $sifaRegistration->is_registered = 0; // Setting registration to "no"
            $sifaRegistration->remarks = $request->remarks;
            $sifaRegistration->save(); // Save the main record first

            // Save the sifa_retirement_and_nomination details
            if ($request->is_registered == 'no' && $request->has('sifa_retirement_and_nomination') && is_array($request->sifa_retirement_and_nomination)) {
                foreach ($request->sifa_retirement_and_nomination as $key => $nomination) {
                    $nominationModel = new SifaRetirementAndNomination();
                    $nominationModel->sifa_registration_id = $sifaRegistration->id; // Reference to the main record
                    $nominationModel->nominee_name = $nomination['nominee_name'] ?? null;
                    $nominationModel->relation_with_employee = $nomination['relation_with_employee'] ?? null;
                    $nominationModel->cid_number = $nomination['cid_number'] ?? null;
                    $nominationModel->percentage_of_share = $nomination['percentage_of_share'] ?? null;
                    $nominationModel->save();
                }
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

            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($sifaRegistration->histories(), $approverByHierarchy, $request->remarks);


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
        $sifaRegistration = SifaRegistration::where('mas_employee_id', $user->id)->with(['SifaNomination', 'SifaDependent', 'SifaDocument'])->findOrFail($id);
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
        $sifaNominations = $sifaRegistration->SifaNomination;
        $sifaDependents = $sifaRegistration->SifaDependent;
        $sifaDocuments = SifaDocument::where('sifa_registration_id', $id)->first();

        // You can also pass other data, such as employee data, if needed
        return view('sifa.sifa-registration.edit', compact('sifaRegistration', 'user', 'sifaDocuments', 'sifaNominations', 'sifaDependents'));
    }

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
            $sifaRegistration->has_been_edited = true;
            $sifaRegistration->save();
            // Notify approver
            $approver = User::whereHas('roles', function ($q) {
                $q->where('role_id', SIFA_MANAGER); 
            })->first();
// dd($approver);
             Mail::to($approver->email)->send(new \App\Mail\SifaEditedNotificationMail($sifaRegistration, $approver->id));
            
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
            \Log::error('error for sifa ' . $sifaRegistration->id . ': ' . $e->getMessage());
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
