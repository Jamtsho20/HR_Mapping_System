<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\SendCredentialsMail;
use App\Models\MasDepartment;
use App\Models\MasDesignation;
use Illuminate\Http\Request;
use App\Models\MasDzongkhag;
use App\Models\MasEmployeeDocument;
use App\Models\MasEmployeeExperience;
use App\Models\MasEmployeeGroup;
use App\Models\MasEmployeeGroupMap;
use App\Models\MasEmployeeJob;
use App\Models\MasEmployeePermenantAddress;
use App\Models\MasEmployeePresentAddress;
use App\Models\MasEmployeeQualification;
use App\Models\MasEmployeeTraining;
use App\Models\MasEmploymentType;
use App\Models\MasGewog;
use App\Models\MasGrade;
use App\Models\MasGradeStep;
use App\Models\MasOffice;
use App\Models\MasQualification;
use App\Models\MasSection;
use App\Models\Role;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\SAP\ApiController;
use App\Http\Controllers\Api\SOMs\ApiController as SomsApiController;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    protected $apiController;
    protected $somsApiController;

    /**
     * Display a listing of the resource.
     */
    public function __construct(ApiController $apiController, SomsApiController $somsApiController)
    {
        $this->middleware('permission:employee/employee-lists,view')->only('index', 'show');
        $this->middleware('permission:employee/employee-lists,create')->only('store');
        $this->middleware('permission:employee/employee-lists,edit')->only('update', 'edit');
        $this->middleware('permission:employee/employee-lists,delete')->only('destroy');

        $this->apiController = $apiController;
        $this->somsApiController = $somsApiController;
    }

    private $filePath = 'images/employee/';

    public function index(Request $request)
    {
        $privileges = $request->instance();
        // dd(User::get());
        $employees = User::filter($request)->orderBy('name')->paginate(config('global.pagination'))->withQueryString();
        return view('employee/employee-list.index', compact('privileges', 'employees'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $dzongkhags = MasDzongkhag::with('gewogs')->orderBy('dzongkhag')->get(['id', 'dzongkhag']);
        $gewogs = MasGewog::orderBy('name')->get(['id', 'name']);
        $departments = MasDepartment::orderBy('name')->get(['id', 'name']);
        $sections = MasSection::orderBy('name')->get(['id', 'name']);
        $gradeSteps = MasGradeStep::orderBy('name')->get(['id', 'name', 'point']);
        $designations = MasDesignation::orderBy('name')->get(['id', 'name']);
        $grades = MasGrade::orderBy('name')->get(['id', 'name']);
        $employmentTypes = MasEmploymentType::orderBy('name')->get(['id', 'name']);
        $qualifications = MasQualification::orderBy('name')->get(['id', 'name']);
        $offices = MasOffice::orderBy('name')->get(['id', 'name']);
        $fixedEmpId = fixEmployeeId($this->fetchHighestEmpId() + 1);
        $roles = Role::orderBy('id')->get();
        $suffix = MasEmployeeJob::select('suffix')->get();
        $employeeGroups = MasEmployeeGroup::orderBy('name')->whereStatus(1)->get(['id', 'name']);

        return view('employee/employee-list.create', compact('dzongkhags', 'gewogs', 'departments', 'designations', 'grades', 'gradeSteps', 'sections', 'employmentTypes', 'qualifications', 'fixedEmpId', 'offices', 'roles', 'employeeGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employeeId = $request->employee_id ?? "";
        $nextTab = 'address';
        try {
            $employeeId = $this->savePersonalInfo($request->personal, $request);

            return redirect()->route('employee-lists.edit', ['employee_list' => $employeeId, 'tab' => $nextTab])->with('msg_success', 'Data saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $canUpdate = (int) $instance->edit;
        $employee = User::findOrFail($id);
        $employeeGroupNames = MasEmployeeGroupMap::with('masEmpGroup')
            ->where('mas_employee_id', $id)
            ->get()
            ->map(function ($groupMap) {
                return $groupMap->masEmpGroup->name; // Assuming 'name' is the field you want from masEmpGroup
            })
            ->toArray();
        if ($employee->status == 'Draft') {
            return back()->with('msg_error', 'Application status is in draft, so fill up all the detials to view.');
        }
        return view('employee.employee-list.show', compact('employee', 'canUpdate', 'employeeGroupNames'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)

    {
        $employee = User::findOrFail($id);
        $dzongkhags = MasDzongkhag::with('gewogs')->orderBy('dzongkhag')->get(['id', 'dzongkhag']);
        $departments = MasDepartment::orderBy('name')->get(['id', 'name']);
        $designations = MasDesignation::orderBy('name')->get(['id', 'name']);
        $grades = MasGrade::orderBy('name')->get(['id', 'name']);
        $employmentTypes = MasEmploymentType::orderBy('name')->get(['id', 'name']);
        $qualifications = MasQualification::orderBy('name')->get(['id', 'name']);
        $offices = MasOffice::orderBy('name')->get(['id', 'name']);
        $roles = Role::orderBy('id')->get();
        $rolesAssigned = $employee->roles->pluck('id')->toArray();
        $employeeGroups = MasEmployeeGroup::orderBy('name')->whereStatus(1)->get(['id', 'name']);
        $employeeGroupMaps = MasEmployeeGroupMap::where('mas_employee_id', $id)->pluck('mas_employee_group_id')->toArray();
        $points = [];
        $startingSalary = 0;
        $increment = 0;
        $endingSalary = 0;
        $selectedPoint = 0;
        if (!is_null($employee->empJob) && $employee->empJob->mas_grade_step_id) {
            $gradeStep = MasGradeStep::where('id', $employee->empJob->mas_grade_step_id)->first();
            $startingSalary = $gradeStep->starting_salary;
            $endingSalary = $gradeStep->ending_salary;
            $increment = $gradeStep->increment;
            $points = range(1, $gradeStep->point);
        }
        if (!is_null($employee->empJob) && $employee->empJob->basic_pay >= $startingSalary && $increment != 0) {
            $selectedPoint = (($employee->empJob->basic_pay - $startingSalary) / $increment) + 1;
        }
        return view('employee.employee-list.edit', compact('employee', 'dzongkhags', 'departments', 'designations', 'grades', 'employmentTypes', 'qualifications', 'offices', 'roles', 'rolesAssigned', 'employeeGroups', 'employeeGroupMaps', 'points', 'selectedPoint'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $tab = $request->input('current_tab');
        $this->savePersonalInfo($request->personal, $request, $id);
        if ($tab == null) {
            $nextTab = 'address';
            return redirect()->route('employee-lists.edit', ['employee_list' => $id, 'tab' => $nextTab])->with('msg_success', 'Data saved successfully.');
        }
        if ($tab === 'address') {
            $this->saveAddress($request->permenant_address, $request->current_address, $id, $request);
        } elseif ($tab === 'job') {
            $this->saveJob($request->job, $id, $request);
        } elseif ($tab === 'qualification') {
            $this->saveQualifications($request->qualifications, $id, $request);
        } elseif ($tab === 'training') {
            $this->saveTrainings($request->trainings, $id, $request);
        } elseif ($tab === 'experience') {
            $this->saveExperiences($request->experiences, $id, $request);
        } elseif ($tab === 'document') {
            $this->saveDocuments($request->documents, $id, $request);
        } else if ($tab === 'role') {

            // $this->assignRoles($request->roles, $id, $request);
            if (!$request->roles) {
                return redirect()->back()->with('msg_error', 'You need to select at least one role');
            }
            try {
                DB::beginTransaction();

                $this->assignRoles($request->documents, $id, $request);

                // $applicationStatus = User::where('id', $id)->value('status');
                // if($applicationStatus === 'Draft'){
                DB::table('mas_employees')->where('id', $id)->whereStatus(0)->update(['status' => 1]);


                // }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
                // return back()->withInput()->with('msg_error', $e->getMessage());
            }
            $employee = User::where('id', $id)->first();

            if ($employee->status == 'Completed' && !$employee->registered_email_sent) {
                Mail::to($employee->email)->send(new SendCredentialsMail($employee, config('global.default_password')));
            }
            $employee->registered_email_sent = true;
            $employee->save();
            if ($employee->status == 'Completed') {
                $this->postEmployeeToSap($employee);
                $this->postEmployeeToSoms($employee);
                // $somsData = $this->prepareSomsData();
            }
            if (!$employee->appointment_order) {
                $this->appointmentOrder($employee);
            }

            return redirect()->route('employee-lists.index')->with('msg_success', 'Employee updated successfully');
        }
        $nextTab = $this->getNextTab($tab);
        return redirect()->route('employee-lists.edit', ['employee_list' => $id, 'tab' => $nextTab])->with('msg_success', 'Data saved successfully for ' . $tab . '.');
    }

    public function appointmentOrder($employee)
    {
        $pdf = Pdf::loadView('office-order.appointment-order-pdf', compact('employee'));

        $fileName = 'AO-' . $employee->username . '.pdf';
        $filePath = 'office-order/appointment-order/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        $employee->appointment_order = $filePath;
        $employee->save();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::findOrFail($id)->delete();

            return back()->with('msg_success', 'The employee has been deleted successfully');
        } catch (\Exception $exception) {
            return back()->with('msg_error', 'The employee cannot be deleted as it has been used and is linked with other modules. For further information contact system admin.');
        }
    }

    private function savePersonalInfo($personalInfo, $request, $employeeId = null)
    {
        $user = $employeeId ? User::findOrFail($employeeId) : "";
        $rules = [
            'personal.first_name' => 'required',
            'personal.title' => 'required',
            'personal.cid_no' => 'required|digits:11',
            'personal.gender' => 'required',
            'personal.dob' => 'required|date',
            'personal.birth_place' => '',
            'personal.birth_country' => '',
            'personal.marital_status' => 'required',
            'personal.email' => 'required|email|unique:mas_employees,email,' . ($employeeId ?? 'null'),
            'personal.contact_number' => 'required|digits:8',
            'personal.nationality' => 'required',
            'personal.date_of_appointment' => 'required|date',
            'personal.cid_copy' => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',
        ];
        $request->validate($rules);

        if (!$user || !isset($personalInfo['cid_copy'])) {
            $rules['personal.cid_copy'] = 'required|file|mimes:jpg,jpeg,png|max:2048';
        }

        // Handle profile picture upload
        if (isset($personalInfo['profile_pic'])) {
            // Delete existing profile pic if it exists
            if ($user && $user->profile_pic) {
                unlink($user->profile_pic); // Deletes the old profile pic from storage
            }
            // Upload new profile picture and update the path
            $profilePic = uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/');
        } else {
            $profilePic = $user ? $user->profile_pic : null;
        }

        // Handle CID copy upload
        if (isset($personalInfo['cid_copy'])) {
            // Delete existing CID copy if it exists
            if ($user && $user->cid_copy) {
                unlink($user->cid_copy); // Deletes the old CID copy from storage
            }
            // Upload new CID copy and update the path
            $empCidCopy = uploadImageToDirectory($personalInfo['cid_copy'], $this->filePath);
        } elseif ($employeeId) {
            // Retain the existing CID copy if no new one is uploaded
            $empCidCopy = $user ? $user->cid_copy : null;
        } else {
            throw new \Exception('Please upload the employee CID copy.');
        }

        // Prepare the data to be saved
        $userData = [
            'first_name' => $personalInfo['first_name'] ?? null,
            'middle_name' => $personalInfo['middle_name'] ?? null,
            'last_name' => $personalInfo['last_name'] ?? null,
            'title' => $personalInfo['title'] ?? null,
            'name' => trim($personalInfo['first_name'] . ' ' . ($personalInfo['middle_name'] ?? '') . ' ' . ($personalInfo['last_name'] ?? '')),
            'username' => $user->username ?? fixEmployeeId($this->fetchHighestEmpId() + 1),
            'employee_id' => $user->employee_id ?? $this->fetchHighestEmpId() + 1,
            'password' => bcrypt(config('global.default_password')),
            'email' => $personalInfo['email'],
            'cid_no' => $personalInfo['cid_no'],
            'gender' => $personalInfo['gender'],
            'dob' => $personalInfo['dob'],
            'birth_place' => $personalInfo['birth_place'],
            'birth_country' => $personalInfo['birth_country'],
            'marital_status' => $personalInfo['marital_status'],
            'contact_number' => $personalInfo['contact_number'],
            'nationality' => $personalInfo['nationality'],
            'date_of_appointment' => $personalInfo['date_of_appointment'],
            'is_active' => $personalInfo['is_active'],
            'profile_pic' => $profilePic,
            'cid_copy' => $empCidCopy,
            'status' => $request->status ?? ($user->status == 'Completed' ? 1 : 0),
        ];
        // Update or create the user
        $user = User::updateOrCreate(
            ['id' => $employeeId], // Conditions to find the user
            $userData // Data to update or create
        );

        return $user->id;
    }


    private function saveAddress($permanentAddress, $currentAddress, $employeeId, $request)
    {
        $request->validate([
            // Permanent address validation rules
            'permenant_address.mas_dzongkhag_id' => 'required',
            'permenant_address.mas_gewog_id' => 'required',
            'permenant_address.mas_village_id' => 'required',
            'permenant_address.thram_no' => 'required',
            'permenant_address.house_no' => 'required',
            // Current address validation rules
            'current_address.mas_dzongkhag_id' => 'required',
            'current_address.city' => 'required',
            'current_address.postal_code' => 'required',
        ]);

        $empPermanentAddress = MasEmployeePermenantAddress::updateOrCreate(
            ['mas_employee_id' => $employeeId],
            [
                'mas_employee_id' => $employeeId,
                'mas_dzongkhag_id' => $permanentAddress['mas_dzongkhag_id'] ?? null,
                'mas_gewog_id' => $permanentAddress['mas_gewog_id'] ?? null,
                'mas_village_id' => $permanentAddress['mas_village_id'] ?? null,
                'thram_no' => $permanentAddress['thram_no'] ?? null,
                'house_no' => $permanentAddress['house_no'] ?? null,
            ]
        );

        $empCurrentAddress = MasEmployeePresentAddress::updateOrCreate(
            ['mas_employee_id' => $employeeId],
            [
                'mas_employee_id' => $employeeId,
                'mas_dzongkhag_id' => $currentAddress['mas_dzongkhag_id'] ?? null,
                'mas_gewog_id' => $currentAddress['mas_gewog_id'] ?? null,
                'city' => $currentAddress['city'] ?? null,
                'postal_code' => $currentAddress['postal_code'] ?? null,
            ]
        );
    }

    private function saveJob($job, $employeeId, $request)
    {
        $messages = [
            'mas_office_id.required' => 'Job location field is required',
            'job.bank.required_if' => 'The bank field is required when the salary disbursement mode is saving account.',
            'job.account_number.requiredif' => 'The account number field is required when the salary disbursement mode is saving account.'
        ];
        $request->validate([
            'job.mas_department_id' => 'required',
            // 'job.mas_section_id' => 'required',
            'job.mas_designation_id' => 'required',
            'job.mas_grade_id' => 'required',
            'job.mas_grade_step_id' => 'required',
            'job.mas_employment_type_id' => 'required',
            'job.basic_pay' => 'required',
            'job.mas_office_id' => 'required',
            'job.salary_disbursement_mode' => 'required',
            'job.bank' => 'required_if:job.salary_disbursement_mode,2',
            'job.account_number' => 'requiredif:salary_disbursement_mode,2',
            'job.pf_number' => 'required',
            'job.tpn_number' => 'required',
            'job.gis_policy_number' => '',
        ], $messages);

        $empJob = MasEmployeeJob::updateOrCreate(
            ['mas_employee_id' => $employeeId],
            [
                'mas_employee_id' => $employeeId,
                'mas_department_id' => $job['mas_department_id'],
                'mas_section_id' => $job['mas_section_id'] ?? null,
                'mas_designation_id' => $job['mas_designation_id'],
                'suffix' => $job['suffix'],
                'mas_grade_id' => $job['mas_grade_id'],
                'mas_grade_step_id' => $job['mas_grade_step_id'],
                'has_probation' => $job['mas_employment_type_id'] == 2 ? 1 : 0,
                'mas_employment_type_id' => $job['mas_employment_type_id'],
                'immediate_supervisor' => $job['immediate_supervisor'] ?? null,
                'mas_office_id' => $job['mas_office_id'],
                'basic_pay' => $job['basic_pay'],
                'salary_disbursement_mode' => $job['salary_disbursement_mode'],
                'bank' => $job['bank'] ?? null,
                'account_number' => $job['account_number'] ?? null,
                'pf_number' => $job['pf_number'],
                'tpn_number' => $job['tpn_number'],
                'gis_policy_number' => $job['gis_policy_number'],
            ]
        );

        // Handle employee group mapping
        if (!empty($job['employee_group'])) {
            $empGroupMaps = [];
            // Prepare data for employee groups
            foreach ($job['employee_group'] as $value) {
                $empGroupMaps[] = [
                    'mas_employee_id' => $employeeId,
                    'mas_employee_group_id' => $value,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Delete existing mappings for this employee to avoid duplicates
            MasEmployeeGroupMap::where('mas_employee_id', $employeeId)->delete();
            // Insert new mappings
            MasEmployeeGroupMap::insert($empGroupMaps);
        }
    }

    private function saveQualifications($qualifications, $employeeId, $request)
    {
        $filteredQualifications = array_filter($qualifications, function ($qualification) {
            return !empty($qualification['school']) ||
                !empty($qualification['completion_year']) ||
                !empty($qualification['subject']) ||
                !empty($qualification['aggregate_score']);
        });
        $qualificationIdsInRequest = []; // Track IDs from the request

        foreach ($filteredQualifications as $key => $value) {
            $qualificationId = $value['mas_qualification_id'] ?? null;
            $qualificationIdsInRequest[] = $qualificationId;

            // Use updateOrCreate to either update the existing record or create a new one
            MasEmployeeQualification::updateOrCreate(
                [
                    'mas_employee_id' => $employeeId,
                    'mas_qualification_id' => $qualificationId
                ],
                [
                    'school' => $value['school'],
                    'subject' => $value['subject'],
                    'completion_year' => $value['completion_year'],
                    'aggregate_score' => $value['aggregate_score']
                ]
            );
        }

        // Handle deleting records that are no longer in the request
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            MasEmployeeQualification::where('mas_employee_id', $employeeId)
                ->whereNotIn('mas_qualification_id', $qualificationIdsInRequest)
                ->delete();
        }
    }

    private function saveTrainings($trainings, $employeeId, $request)
    {
        $filteredTrainings = array_filter($trainings, function ($training) {
            return !empty($training['title']) ||
                !empty($training['start_date']) ||
                !empty($training['end_date']) ||
                !empty($training['duration']) ||
                !empty($training['location']) ||
                !empty($training['description']) ||
                !empty($training['certificate']);
        });
        $trainingIdsInRequest = []; // Track IDs from the request

        foreach ($filteredTrainings as $key => $value) {
            $trainingId = $value['id'] ?? null; // Assume the form might include an id for existing records
            $trainingCertificate = isset($value['certificate']) ? uploadImageToDirectory($value['certificate'], $this->filePath) : null;

            // Use updateOrCreate to either update the existing record or create a new one
            $training = MasEmployeeTraining::updateOrCreate(
                [
                    'id' => $trainingId, // If the trainingId is null, it will create a new record
                    'mas_employee_id' => $employeeId
                ],
                [
                    'title' => $value['title'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'duration' => $value['duration'],
                    'location' => $value['location'],
                    'description' => $value['description'],
                    'certificate' => $trainingCertificate
                ]
            );

            // Add the ID of the created or updated record to the array
            $trainingIdsInRequest[] = $training->id;
        }

        // Handle deleting records that are no longer in the request
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            MasEmployeeTraining::where('mas_employee_id', $employeeId)
                ->whereNotIn('id', $trainingIdsInRequest)
                ->delete();
        }
    }

    private function saveExperiences($experiences, $employeeId, $request)
    {
        $filteredExperiences = array_filter($experiences, function ($experience) {
            return !empty($experience['organization']) ||
                !empty($experience['place']) ||
                !empty($experience['designation']) ||
                !empty($experience['start_date']) ||
                !empty($experience['end_date']) ||
                !empty($experience['certificate']);
        });
        $experienceIdsInRequest = []; // Track IDs from the request

        foreach ($filteredExperiences as $value) {
            $experienceId = $value['id'] ?? null; // Use null if 'id' is not set

            // Use updateOrCreate to either update the existing record or create a new one
            $experience = MasEmployeeExperience::updateOrCreate(
                [
                    'id' => $experienceId, // Check for an existing record by ID
                    'mas_employee_id' => $employeeId // Ensure it's for the correct employee
                ],
                [
                    'organization' => $value['organization'],
                    'place' => $value['place'],
                    'designation' => $value['designation'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'description' => $value['description'],
                ]
            );

            // Add the ID of the created or updated record to the array
            $experienceIdsInRequest[] = $experience->id;
        }

        // Handle deleting records that are no longer in the request
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            MasEmployeeExperience::where('mas_employee_id', $employeeId)
                ->whereNotIn('id', $experienceIdsInRequest)
                ->delete();
        }
    }

    private function saveDocuments($doc, $employeeId, $request)
    {
        $rules = [
            'documents.employment_contract' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'documents.non_disclosure_aggrement' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'documents.job_responsibilities' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'documents.*.other' => 'file|mimes:jpg,jpeg,png,pdf|max:2048'
        ];
        $request->validate($rules);

        // Fetch existing employee document, or create an empty object if none exists
        $empDocument = MasEmployeeDocument::whereMasEmployeeId($employeeId)->first() ?? new MasEmployeeDocument();
        if (!$empDocument->employment_contract || !isset($doc['employment_contract'])) {
            $rules['documents.employment_contract'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        if (!$empDocument->non_disclosure_aggrement || !isset($doc['non_disclosure_aggrement'])) {
            $rules['documents.non_disclosure_aggrement'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        if (!$empDocument->job_responsibilities || !isset($doc['job_responsibilities'])) {
            $rules['documents.job_responsibilities'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        if (isset($doc['employment_contract'])) {
            // Remove old file if exists
            if ($empDocument->employment_contract) {
                unlink($empDocument->employment_contract);
            }
            $empContract = uploadImageToDirectory($doc['employment_contract'], $this->filePath);
        } else {
            $empContract = $empDocument->employment_contract;
        }

        // Handle non-disclosure agreement
        if (isset($doc['non_disclosure_aggrement'])) {
            if ($empDocument->non_disclosure_aggrement) {
                unlink($empDocument->non_disclosure_aggrement);
            }
            $empNonDisclosureAggrement = uploadImageToDirectory($doc['non_disclosure_aggrement'], $this->filePath);
        } else {
            $empNonDisclosureAggrement = $empDocument->non_disclosure_aggrement;
        }

        // Handle job responsibilities
        if (isset($doc['job_responsibilities'])) {
            if ($empDocument->job_responsibilities) {
                unlink($empDocument->job_responsibilities);
            }
            $jobResponsibilities = uploadImageToDirectory($doc['job_responsibilities'], $this->filePath);
        } else {
            $jobResponsibilities = $empDocument->job_responsibilities;
        }

        // Handle 'other' documents


        if (isset($doc['other']) || $request['existing_documents']) {

            // Combine existing documents with new ones
            if (isset($request['existing_documents']) && is_array($request['existing_documents'])) {
                // Use existing documents and upload new ones

                $existingDocuments = $request['existing_documents'];
            } else {

                $existingDocuments = [];
            }

            // Upload new files and merge with existing
            $uploadedDocuments = array_map(fn($file) => uploadImageToDirectory($file, $this->filePath), isset($doc['other']) ? $doc['other'] : []);
            $otherDocuments = array_merge($existingDocuments, $uploadedDocuments);
        } else {

            if (isset($empDocument->other)) {

                delete_image($empDocument->other); // delete from the storage

                // delete from db
                $empDocument->other = null;
                $empDocument->save();
            }

            // If no new documents are provided, just use existing ones if available
            $otherDocuments = $empDocument->other ? json_decode($empDocument->other, true) : [];
        }

        // Update or create the document entry
        MasEmployeeDocument::updateOrCreate(
            ['mas_employee_id' => $employeeId],
            [
                'employment_contract' => $empContract,
                'non_disclosure_aggrement' => $empNonDisclosureAggrement,
                'job_responsibilities' => $jobResponsibilities,
                'other' => empty($otherDocuments) ? null : json_encode($otherDocuments),
            ]
        );
    }
    private function assignRoles($roles, $id, $request)
    {
        $user = User::findOrFail($id);
        $rolesAssigned = [];
        foreach ($request->roles as $key => $value) {
            $rolesAssigned[$value] = [
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ];
        }

        $user->roles()->sync($rolesAssigned);
    }

    private function getNextTab($currentTab)
    {
        $tabs = ['address', 'job', 'qualification', 'training', 'experience', 'document', 'role'];

        $currentIndex = array_search($currentTab, $tabs);
        $nextIndex = $currentIndex !== false && $currentIndex < count($tabs) - 1 ? $currentIndex + 1 : 0;

        return $tabs[$nextIndex];
    }

    private function fetchHighestEmpId()
    {
        return User::where('username', '<>', 'SAP000')->max('employee_id');
    }

    private function postEmployeeToSap($employee)
    {
        // dd($employee);
        $tpnNo = MasEmployeeJob::where('mas_employee_id', $employee->id)->value('tpn_number');
        $sapData = [
            'CardCode' => $employee->username,
            'CardName' => trim($employee->first_name . ' ' . ($employee->middle_name ?? '') . ' ' . ($employee->last_name ?? '')),
            'CardType' => 'C',
            'GroupCode' => 108,
            'Currency' => '',
            'CardForeignName' => $employee->cid_no,
            'Country' => 'BT',
            'DebitorAccount' => '34611',
            'DownPaymentInterimAccount' => '23245',
            "BPFiscalTaxIDCollection" =>
                [
            "BPFiscalTaxIDCollection" =>
            [

                    ["TaxId0" =>  "00" . $tpnNo] // Prefix 00 to Employee ID
                ]
                
            ]
        ];
        $this->apiController->postEmployeeToSap($sapData);
    }

    private function postEmployeeToSoms($employee)
    {
        $masOfficeId = MasEmployeeJob::where('mas_employee_id', $employee->id)->value('mas_office_id');
        $empDzongkhag = \DB::select("
            select
                t2.dzongkhag
            from mas_offices t1
                left join mas_dzongkhags t2 on t2.id = t1.mas_dzongkhag_id
            where t1.id = ?
        ", [$masOfficeId])[0];

        $somsData = [
            'employee_code' => str_replace('E', '', $employee->username),
            // 'employee_code' => 2005,
            'person_id' => $employee->id,
            'first_name' => $employee->first_name,
            'middle_name' => $employee->middle_name,
            'last_name' => $employee->last_name,
            'title' => $employee->title,
            'sex' => config('global.gender')[$employee->gender],
            'dateOfBirth' => $employee->dob,
            'mobile_number' => $employee->contact_number,
            'email_address' => $employee->email,
            'status' => $employee->is_active,
            'locatorId' => $empDzongkhag->dzongkhag,
        ];
        $this->somsApiController->postEmployeeToSoms($somsData);
    }
    public function showRegularizeDetails(Request $request)
    {

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $employees = User::filter($request)
            ->whereHas('empJob.empType', function ($query) {
                $query->where('id', 3);
            })
            ->whereBetween(
                DB::raw("DATE_ADD(date_of_appointment, INTERVAL 6 MONTH)"),
                [$startOfMonth, $endOfMonth]
            )
            ->orderBy('name')
            ->paginate(config('global.pagination'))
            ->withQueryString();
        return view('employee/regularize-list.index', compact('employees'));
    }

    public function toggleStatus(Request $request)
    {
        try {
            $record = User::findOrFail($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->is_regularized = $request->is_regularized;
            $record->save();

            DB::commit();

            // $record->regularized_on = Carbon::now();
            // $record->save();

            // if (!$record->regular_appointment_order && $record->is_regularized == 1) {
            //     $this->RegularappointmentOrder($record);
            // }

            return response()->json(['success' => true, 'message' => 'regularize status updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating regularize status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the regularize status.',
                'refreshPage' => true
            ]);
        }
    }

    public function generateRegularAO(Request $request)
    {
        DB::beginTransaction();
        try {
            $record = User::findOrFail($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }

            // Ensure empJob exists
            if (!$record->empJob) {
                return response()->json(['success' => false, 'message' => 'Employee job details not found'], 404);
            }

            // Update the employee record and related job details
            $record->regularized_on = Carbon::now();
            $record->empJob->mas_employment_type_id = 2;
            $record->empJob->save();
            $record->save();

            // Generate the regular appointment order if required
            if (!$record->regular_appointment_order && $record->is_regularized == 1) {
                try {
                    $this->RegularappointmentOrder($record);
                } catch (\Exception $e) {
                    throw new \Exception("Error in RegularappointmentOrder: " . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Appointment Order generated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating Appointment order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the Appointment order.',
                'refreshPage' => true
            ]);
        }
    }

    public function RegularappointmentOrder($employee)
    {
        $pdf = Pdf::loadView('office-order.regular-appointment-order-pdf', compact('employee'));

        $fileName = 'RAO-' . $employee->username . '.pdf';
        $filePath = 'office-order/appointment-order/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        $employee->regular_appointment_order = $filePath;
        $employee->save();
    }
}
