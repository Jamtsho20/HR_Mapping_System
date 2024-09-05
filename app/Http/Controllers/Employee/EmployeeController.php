<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasDesignation;
use Illuminate\Http\Request;
use App\Models\MasDzongkhag;
use App\Models\MasEmployeeDocument;
use App\Models\MasEmployeeExperience;
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
use App\Models\MasVillage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:employee/employee-lists,view')->only('index', 'show');
        $this->middleware('permission:employee/employee-lists,create')->only('store');
        $this->middleware('permission:employee/employee-lists,edit')->only('update', 'edit');
        $this->middleware('permission:employee/employee-lists,delete')->only('destroy');
    }

    private $filePath = 'images/employee/';

    public function index(Request $request)
    {
        $privileges = $request->instance();
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
        $gradeSteps = MasGradeStep::orderBy('name')->get(['id', 'name']);
        $designations = MasDesignation::orderBy('name')->get(['id', 'name']);
        $grades = MasGrade::orderBy('name')->get(['id', 'name']);
        $employmentTypes = MasEmploymentType::orderBy('name')->get(['id', 'name']);
        $qualifications = MasQualification::orderBy('name')->get(['id', 'name']);
        $offices = MasOffice::orderBy('name')->get(['id', 'name']);
        $fixedEmpId = fixEmployeeId($this->fetchHighestEmpId() + 1);

        return view('employee/employee-list.create', compact('dzongkhags', 'gewogs', 'departments', 'designations', 'grades', 'gradeSteps', 'sections', 'employmentTypes', 'qualifications', 'fixedEmpId', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employeeId = $request->employee_id ?? "";

        try {
            $employeeId = $this->savePersonalInfo($request->personal, $request);

            return redirect()->route('employee-lists.edit', $employeeId)->with('msg_success', 'Data saved successfully.');
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
        if ($employee->status == 'Draft') {
            return back()->with('msg_error', 'Application status is in draft, so fill up all the detials to view.');
        }
        return view('employee.employee-list.show', compact('employee', 'canUpdate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)

    {
        $employee = User::findOrFail($id);

        $dzongkhags = MasDzongkhag::with('gewogs')->orderBy('dzongkhag')->get(['id', 'dzongkhag']);
        $gewogs = MasGewog::orderBy('name')->get(['id', 'name']);
        $villages = MasVillage::orderBy('village')->get(['id', 'village']);
        $departments = MasDepartment::orderBy('name')->get(['id', 'name']);
        $sections = MasSection::orderBy('name')->get(['id', 'name']);
        $designations = MasDesignation::orderBy('name')->get(['id', 'name']);
        $grades = MasGrade::orderBy('name')->get(['id', 'name']);
        $gradeSteps = MasGradeStep::orderBy('name')->get(['id', 'name']);
        $employmentTypes = MasEmploymentType::orderBy('name')->get(['id', 'name']);
        $qualifications = MasQualification::orderBy('name')->get(['id', 'name']);
        $offices = MasOffice::orderBy('name')->get(['id', 'name']);

        return view('employee.employee-list.edit', compact('employee', 'dzongkhags', 'gewogs', 'villages', 'departments', 'sections', 'designations', 'grades', 'gradeSteps', 'employmentTypes', 'qualifications', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tab = $request->input('current_tab');
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
            DB::beginTransaction();
            try {
                $this->saveDocuments($request->documents, $id, $request);
                $masEmployee = DB::table('mas_employees')->where('id', $id)->update(['status' => 1]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->with('msg_error', $e->getMessage());
            }
            return redirect()->route('employee-lists.index')->with('msg_success', 'Employee updated successfully');
        }
        $nextTab = $this->getNextTab($tab);
        return redirect()->route('employee-lists.edit', ['employee_list' => $id, 'tab' => $nextTab])->with('msg_success', 'Data saved successfully for ' . $tab . '.');
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
        $request->validate([
            'personal.email' => 'required|email|unique:mas_employees,email,' . ($employeeId ?? 'null'),
            'personal.first_name' => 'required',
            'personal.title' => 'required',
            'personal.cid_no' => 'required|digits:11',
            'personal.gender' => 'required',
            'personal.dob' => 'required|date',
            'personal.birth_place' => 'required',
            'personal.birth_country' => 'required',
            'personal.marital_status' => 'required',
            'personal.contact_number' => 'required|digits:8',
            'personal.nationality' => 'required',
            'personal.date_of_appointment' => 'required|date',
            'personal.cid_copy' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        $profilePic = isset($personalInfo['profile_pic']) ? uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/') : null;
        $empCidCopy = "";
        if (isset($personalInfo['cid_copy'])) {
            $file = $personalInfo['cid_copy'];
            $empCidCopy = uploadImageToDirectory($file, $this->filePath);
        } elseif ($employeeId) {
            $user = User::findOrFail($employeeId);
            $empCidCopy = $user->cid_copy;
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
            'username' => $employeeId ? null : fixEmployeeId($this->fetchHighestEmpId() + 1),
            'employee_id' => $this->fetchHighestEmpId() + 1,
            'password' => bcrypt('password'),
            'email' => $personalInfo['email'] ?? null,
            'cid_no' => $personalInfo['cid_no'] ?? null,
            'gender' => $personalInfo['gender'] ?? null,
            'dob' => $personalInfo['dob'] ?? null,
            'birth_place' => $personalInfo['birth_place'] ?? null,
            'birth_country' => $personalInfo['birth_country'] ?? null,
            'marital_status' => $personalInfo['marital_status'] ?? null,
            'contact_number' => $personalInfo['contact_number'] ?? null,
            'nationality' => $personalInfo['nationality'] ?? null,
            'date_of_appointment' => $personalInfo['date_of_appointment'] ?? null,
            'is_active' => $personalInfo['is_active'] ?? null,
            'profile_pic' => $profilePic,
            'cid_copy' => $empCidCopy,
            'status' => $request->status,
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
        $request->validate([
            'job.mas_department_id' => 'required',
            'job.mas_section_id' => 'required',
            'job.mas_designation_id' => 'required',
            'job.mas_grade_id' => 'required',
            'job.mas_grade_step_id' => 'required',
            'job.mas_employment_type_id' => 'required',
            'job.basic_pay' => 'required',
            'job.bank' => 'required',
            'job.account_number' => 'required',
            'job.pf_number' => 'required',
            'job.tpn_number' => 'required',
        ]);

        $empJob = MasEmployeeJob::updateOrCreate(
            ['mas_employee_id' => $employeeId],
            [
                'mas_employee_id' => $employeeId,
                'mas_department_id' => $job['mas_department_id'],
                'mas_section_id' => $job['mas_section_id'],
                'mas_designation_id' => $job['mas_designation_id'],
                'mas_grade_id' => $job['mas_grade_id'],
                'mas_grade_step_id' => $job['mas_grade_step_id'],
                'mas_employment_type_id' => $job['mas_employment_type_id'],
                'immediate_supervisor' => $job['immediate_supervisor'] ?? null,
                'mas_office_id' => $job['mas_office_id'],
                'basic_pay' => $job['basic_pay'],
                'bank' => $job['bank'],
                'account_number' => $job['account_number'],
                'pf_number' => $job['pf_number'],
                'tpn_number' => $job['tpn_number'],
            ]
        );
    }

    private function saveQualifications($qualifications, $employeeId, $request)
    {
        $qualificationIdsInRequest = []; // Track IDs from the request

        foreach ($qualifications as $key => $value) {
            $qualificationId = $value['mas_qualification_id'];
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
        $trainingIdsInRequest = []; // Track IDs from the request

        foreach ($trainings as $key => $value) {
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
        $experienceIdsInRequest = []; // Track IDs from the request

        foreach ($experiences as $value) {
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
        $request->validate([
            'documents.employment_contract' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'documents.non_disclosure_aggrement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'documents.job_responsibilities' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'documents.*.other' => 'file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Fetch existing employee document, or create an empty object if none exists
        $empDocument = MasEmployeeDocument::whereMasEmployeeId($employeeId)->first() ?? new MasEmployeeDocument();

        // Handle file uploads with fallback to existing documents
        $empContract = isset($doc['employment_contract'])
            ? uploadImageToDirectory($doc['employment_contract'], $this->filePath)
            : $empDocument->employment_contract;

        $empNonDisclosureAggrement = isset($doc['non_disclosure_aggrement'])
            ? uploadImageToDirectory($doc['non_disclosure_aggrement'], $this->filePath)
            : $empDocument->non_disclosure_aggrement;

        $jobResponsibilities = isset($doc['job_responsibilities'])
            ? uploadImageToDirectory($doc['job_responsibilities'], $this->filePath)
            : $empDocument->job_responsibilities;

        // Handle 'other' documents
        $otherDocuments = isset($doc['other'])
            ? array_map(fn($file) => uploadImageToDirectory($file, $this->filePath), $doc['other'])
            : ($empDocument->other ? json_decode($empDocument->other, true) : []);

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

    private function getNextTab($currentTab)
    {
        $tabs = ['address', 'job', 'qualification', 'training', 'experience', 'document'];

        $currentIndex = array_search($currentTab, $tabs);
        $nextIndex = $currentIndex !== false && $currentIndex < count($tabs) - 1 ? $currentIndex + 1 : 0;

        return $tabs[$nextIndex];
    }

    private function fetchHighestEmpId()
    {
        return User::max('employee_id');
    }
}
