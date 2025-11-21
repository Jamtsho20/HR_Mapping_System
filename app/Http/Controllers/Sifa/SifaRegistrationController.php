<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
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
        'sifa_nomination.*.attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',

        'sifa_dependents' => 'required|array|min:1',
        'sifa_dependents.*.dependent_name' => 'required|string|max:255',
        'sifa_dependents.*.relation_with_employee' => 'required|string|max:255',
        'sifa_dependents.*.cid_number' => 'required|string|max:11',
        'sifa_dependents.*.attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',

        'family_tree' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'marriage_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'family_tree_spouse' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
        //dd($request->all());
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
            if ($request->is_registered == 'no') {
                // Set is_registered to 0 and save remarks only
                $sifaRegistration->is_registered = 0;
                $sifaRegistration->save();
            } else {
                // If "Yes", set is_registered to 1 (default behavior)

                $sifaRegistration->sifa_type_id = $sifaTypeId;
                $sifaRegistration->is_registered = 1;
            }

            $sifaRegistration->save(); // Save the SifaRegistration record

            // If the user selects "Yes", process the nomination, dependents, and documents
            if ($request->is_registered == 'yes') {
                // Loop through and store the nominations
                foreach ($request->sifa_nomination as $key => $nominationData) {
                    $sifaNomination = new SifaNomination();
                    $sifaNomination->sifa_registration_id = $sifaRegistration->id;
                    $sifaNomination->nominee_name = $nominationData['nominee_name'];
                    $sifaNomination->relation_with_employee = $nominationData['relation_with_employee'];
                    $sifaNomination->cid_number = $nominationData['cid_number'];
                    $sifaNomination->percentage_of_share = $nominationData['percentage_of_share'];

                    if ($request->hasFile("sifa_nomination.$key.attachment")) {
                        $file = $request->file("sifa_nomination.$key.attachment");
                        $sifaNomination->attachment = uploadImageToDirectory($file, $this->filePath);
                    }

                    $sifaNomination->save();
                }
                // Store SIFA Dependents Data
                foreach ($request->sifa_dependents as $key => $dependentData) {
                    $sifaDependent = new SifaDependent();
                    $sifaDependent->sifa_registration_id = $sifaRegistration->id;
                    $sifaDependent->dependent_name = $dependentData['dependent_name'];
                    $sifaDependent->relation_with_employee = $dependentData['relation_with_employee'];
                    $sifaDependent->cid_number = $dependentData['cid_number'];

                    if ($request->hasFile("sifa_dependents.$key.attachment")) {
                        $file = $request->file("sifa_dependents.$key.attachment");
                        $sifaDependent->attachment = uploadImageToDirectory($file, $this->filePath);
                    }

                    $sifaDependent->save();
                }

                // Store SIFA Documents Data
                $data = ['sifa_registration_id' => $sifaRegistration->id];


                // Loop through fields with single file uploads
                foreach (['family_tree', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced', 'former_spouse'] as $field) {
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
            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has applied SIFA Registration for your endorsement.';
                $emailSubject = 'Advance';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
            }
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
        $approvalDetail = getApplicationLogs(\App\Models\SifaRegistration::class, $sifaRegistration->id);
        return view('sifa.sifa-registration.show', compact('user', 'sifaRegistration', 'sifaDocuments', 'approvalDetail'));
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
        // $this->validate($request, $this->rules);
        $request->validate([
            /* -------------------------
     | SIFA NOMINATIONS
     ------------------------- */
            'sifa_nomination' => 'required|array|min:1',
            'sifa_nomination.*.nominee_name' => 'required|string',
            'sifa_nomination.*.relation_with_employee' => 'required|string',
            'sifa_nomination.*.cid_number' => 'required|string',
            'sifa_nomination.*.percentage_of_share' => 'required|numeric',
            'sifa_nomination.*.attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            /* -------------------------
     | SIFA DEPENDENTS
     ------------------------- */
            'sifa_dependents' => 'required|array|min:1',
            'sifa_dependents.*.dependent_name' => 'required|string',
            'sifa_dependents.*.relation_with_employee' => 'required|string',
            'sifa_dependents.*.cid_number' => 'required|string',
            'sifa_dependents.*.attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            /* -------------------------
     | SIFA DOCUMENTS
     ------------------------- */
            'family_tree'          => 'sometimes|required_without:existing_family_tree|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'spouse_cid'           => 'sometimes|required_without:existing_spouse_cid|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'marriage_certificate' => 'nullable',
            'family_tree_spouse'   => 'nullable',
            'birth_certificate'    => 'nullable',
            'adopted_children'     => 'nullable',
            'if_divorced'          => 'nullable',
            'former_spouse'        => 'nullable',
        ], [

            /* ----- Custom readable messages (Nomination) ----- */
            'sifa_nomination.required' => 'Please add at least one nominee.',
            'sifa_nomination.array'    => 'Nominee data is invalid.',
            'sifa_nomination.*.nominee_name.required' => 'Nominee name is required.',
            'sifa_nomination.*.relation_with_employee.required' => 'Nominee relation is required.',
            'sifa_nomination.*.cid_number.required' => 'Nominee CID number is required.',
            'sifa_nomination.*.percentage_of_share.required' => 'Percentage of share is required.',

            /* ----- Custom readable messages (Dependent) ----- */
            'sifa_dependents.required' => 'Please add at least one dependent.',
            'sifa_dependents.array'    => 'Dependent data is invalid.',
            'sifa_dependents.*.dependent_name.required' => 'Dependent name is required.',
            'sifa_dependents.*.relation_with_employee.required' => 'Dependent relation is required.',
            'sifa_dependents.*.cid_number.required' => 'Dependent CID number is required.',

            /* ----- Custom readable messages (Documents) ----- */
            'family_tree.required'          => 'Family tree document is required.',
            'spouse_cid.required'           => 'CID Copy is required.',

        ]);


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
            foreach ($request->sifa_nomination as $index => $nominationData) {
                $attachmentPath = null;

                // Check for newly uploaded file
                if ($request->hasFile("sifa_nomination.$index.attachment")) {
                    $file = $request->file("sifa_nomination.$index.attachment");
                    $attachmentPath = uploadImageToDirectory($file, $this->filePath);
                }
                // Use existing attachment if no new file uploaded
                elseif (!empty($nominationData['existing_attachment'])) {
                    $attachmentPath = $nominationData['existing_attachment'];
                } else {
                    // If attachment is required, throw exception or return with error
                    throw new \Exception("Attachment is required for nominee " . ($nominationData['nominee_name'] ?? 'Unknown'));
                }

                SifaNomination::updateOrCreate(
                    ['id' => $nominationData['id'] ?? null],
                    [
                        'sifa_registration_id'   => $sifaRegistration->id,
                        'nominee_name'           => $nominationData['nominee_name'],
                        'relation_with_employee' => $nominationData['relation_with_employee'],
                        'cid_number'             => $nominationData['cid_number'],
                        'percentage_of_share'    => $nominationData['percentage_of_share'],
                        'attachment'             => $attachmentPath,
                    ]
                );
            }

            // Update or create new dependents
            foreach ($request->sifa_dependents as $index => $dependentData) {
                $attachmentPath = null;

                // Check if new file uploaded
                if ($request->hasFile("sifa_dependents.$index.attachment")) {
                    $file = $request->file("sifa_dependents.$index.attachment");
                    $attachmentPath = uploadImageToDirectory($file, $this->filePath);
                }
                // Retain old file if no new file uploaded
                elseif (!empty($dependentData['existing_attachment'])) {
                    $attachmentPath = $dependentData['existing_attachment'];
                } else {
                    // You can decide whether to throw error if required
                    throw new \Exception("Attachment is required for dependent " . ($dependentData['dependent_name'] ?? 'Unknown'));
                }

                SifaDependent::updateOrCreate(
                    ['id' => $dependentData['id'] ?? null],
                    [
                        'sifa_registration_id'   => $sifaRegistration->id,
                        'dependent_name'         => $dependentData['dependent_name'],
                        'relation_with_employee' => $dependentData['relation_with_employee'],
                        'cid_number'             => $dependentData['cid_number'],
                        'attachment'             => $attachmentPath,
                    ]
                );
            }

            // Handle document uploads
            $data = ['sifa_registration_id' => $sifaRegistration->id];
            foreach (['family_tree', 'marriage_certificate', 'family_tree_spouse', 'spouse_cid', 'birth_certificate', 'adopted_children', 'if_divorced', 'former_spouse'] as $field) {
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
