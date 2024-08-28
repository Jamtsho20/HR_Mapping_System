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
        // $this->middleware('permission:employee/employee-lists,view')->only('show');
    }

    private $filePath = 'images/employee/';
    // private $employeeId = User::max('employee_id') + 1;
    protected $rules = [
        // rules for mas_employees
        'personal.email' => 'required|email|unique:mas_employees,email',
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
        'personal.nationality' => 'required',
        'personal.cid_copy' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //permenat address validation rule
        'permenant_address.mas_dzongkhag_id' => 'required',
        'permenant_address.mas_gewog_id' => 'required',
        'permenant_address.mas_village_id' => 'required',
        'permenant_address.thram_no' => 'required',
        'permenant_address.house_no' => 'required',
        //present address validation rule
        'current_address.mas_dzongkhag_id' => 'required',
        'current_address.city' => 'required',
        'current_address.postal_code' => 'required',
        //validation rule for mas_employee_jobs
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
        //validation rules for documents
        'documents.employment_contract' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'documents.non_disclosure_aggrement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'documents.job_responsibilities' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];

    protected $messages = [];

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
        $employeeId = fixEmployeeId($this->fetchHighestEmpId() + 1);

        return view('employee/employee-list.create', compact('dzongkhags', 'gewogs', 'departments', 'designations', 'grades', 'gradeSteps', 'sections', 'employmentTypes', 'qualifications', 'employeeId', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        DB::beginTransaction();
        try {
            $employeeId = $this->savePersonalInfo($request->personal, null, $request);
            $this->saveAddress($request->permenant_address, $request->current_address, $employeeId, null, $request);
            $this->saveJob($request->job, $employeeId, $request);
            if (!empty($request->qualifications)) {
                $this->saveQualifications($request->qualifications, $employeeId, $request);
            }
            if (!empty($request->trainings)) {
                $this->saveTrainings($request->trainings, $employeeId, $request);
            }
            if (!empty($request->experiences)) {
                $this->saveExperiences($request->experiences, $employeeId, $request);
            }
            $this->saveDocuments($request->documents, $employeeId, $request);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', 'Employee couldnot be created, please try again.');
        }
        return redirect('employee/employee-lists')->with('msg_success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $canUpdate = (int) $instance->edit;
        $employee = User::findOrFail($id);

        // dd($employee->empDoc);
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
        $employeeId = fixEmployeeId($this->fetchHighestEmpId() + 1);

        return view('employee.employee-list.edit', compact('employee', 'dzongkhags', 'gewogs', 'villages', 'departments', 'sections', 'designations', 'grades', 'gradeSteps', 'employmentTypes', 'qualifications', 'employeeId', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this->validate($request, $this->messages);
        DB::beginTransaction();
        try {

            $employeeId =  $this->savePersonalInfo($request->personal, $id, $request);

            $this->saveAddress($request->permenant_address, $request->current_address, $employeeId, $id, $request);
            $this->saveJob($request->job, $employeeId, $request);
            $this->saveQualifications($request->qualifications, $employeeId, $request);
            $this->saveTrainings($request->trainings, $employeeId, $request);
            $this->saveExperiences($request->experiences, $employeeId, $request);
            $this->saveDocuments($request->documents, $employeeId, $request);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->with('msg_error', 'The employee could not be updated, please try again.');
        }
        return redirect('employee/employee-lists')->with('msg_success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // try {
        //     User::findOrFail($id)->delete();

        //     return back()->with('msg_success', 'The employee has been deleted successfully');
        // } catch(\Exception $exception){
        //     return back()->with('msg_error', 'The qualification cannot be deleted as it has been used and is linked with other modules. For further information contact system admin.');
        // }
    }

    private function savePersonalInfo($personalInfo, $id, $request)
    {
        if ($request->isMethod('put') || $request->isMethod('patch')) { //$request->isMethod('put') || $request->isMethod('patch') is for update
            $user = User::findOrfail($id);
            $empCidCopy = "";
            if (isset($personalInfo['cid_copy'])) {
                $file = $personalInfo['cid_copy'];
                $empCidCopy = uploadImageToDirectory($file, $this->filePath);
            } else {
                $empCidCopy = $user->cid_copy;
            }
            $profilePic = isset($personalInfo['profile_pic']) ? uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/') : $user->profile_pic;
            $fullName = trim($personalInfo['first_name'] . ($personalInfo['middle_name'] ? ' ' . $personalInfo['middle_name'] : '') . ($personalInfo['last_name'] ? ' ' . $personalInfo['last_name'] : ''));

            $user->first_name = $personalInfo['first_name'];
            $user->middle_name = $personalInfo['middle_name'];
            $user->last_name = $personalInfo['last_name'];
            $user->title = $personalInfo['title'];
            $user->name = $fullName;
            $user->password = bcrypt('password');
            $user->email = $personalInfo['email'];
            $user->cid_no = $personalInfo['cid_no'];
            $user->gender = $personalInfo['gender'];
            $user->dob = $personalInfo['dob'];
            $user->birth_place = $personalInfo['birth_place'];
            $user->birth_country = $personalInfo['birth_country'];
            $user->marital_status = $personalInfo['marital_status'];
            $user->contact_number = $personalInfo['contact_number'];
            $user->nationality = $personalInfo['nationality'];
            $user->date_of_appointment = $personalInfo['date_of_appointment'];
            $user->is_active = $personalInfo['is_active'];
            $user->profile_pic = $profilePic;
            $user->cid_copy = $empCidCopy;
            $user->save();
        } else {
            $user = new User();
            $employeeId = $this->fetchHighestEmpId() + 1;
            $userName = fixEmployeeId($employeeId);
            $profilePic = isset($personalInfo['profile_pic']) ? uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/') : null;
            $empCidCopy = "";
            if (isset($personalInfo['cid_copy'])) {
                $file = $personalInfo['cid_copy'];
                $empCidCopy = uploadImageToDirectory($file, $this->filePath);
            } else {
                throw new \Exception('Please upload the employee CID copy.');
            }

            $fullName = trim($personalInfo['first_name'] . ($personalInfo['middle_name'] ? ' ' . $personalInfo['middle_name'] : '') . ($personalInfo['last_name'] ? ' ' . $personalInfo['last_name'] : ''));

            $user->first_name = $personalInfo['first_name'];
            $user->middle_name = $personalInfo['middle_name'];
            $user->last_name = $personalInfo['last_name'];
            $user->title = $personalInfo['title'];
            $user->name = $fullName;
            $user->username = $userName;
            $user->password = bcrypt('password');
            $user->email = $personalInfo['email'];
            $user->cid_no = $personalInfo['cid_no'];
            $user->employee_id = $employeeId;
            $user->gender = $personalInfo['gender'];
            $user->dob = $personalInfo['dob'];
            $user->birth_place = $personalInfo['birth_place'];
            $user->birth_country = $personalInfo['birth_country'];
            $user->marital_status = $personalInfo['marital_status'];
            $user->contact_number = $personalInfo['contact_number'];
            $user->nationality = $personalInfo['nationality'];
            $user->date_of_appointment = $personalInfo['date_of_appointment'];
            $user->is_active = $personalInfo['is_active'];
            $user->profile_pic = $profilePic;
            $user->cid_copy = $empCidCopy;
            $user->save();
        }

        return $user->id;
    }



    private function saveAddress($permenantAddress, $currentAddress, $employeeId, $id, $request)
    {

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            $empPermenantAddress = MasEmployeePermenantAddress::where('mas_employee_id', $id)->firstOrFail();
            $empCurrentAddress = MasEmployeePresentAddress::where('mas_employee_id', $id)->firstOrFail();
        } else {
            $empPermenantAddress = new MasEmployeePermenantAddress();
            $empCurrentAddress = new MasEmployeePresentAddress();
        }

        $empPermenantAddress->mas_employee_id = $employeeId;
        $empPermenantAddress->mas_dzongkhag_id = $permenantAddress['mas_dzongkhag_id'];
        $empPermenantAddress->mas_gewog_id = $permenantAddress['mas_gewog_id'];
        $empPermenantAddress->mas_village_id = $permenantAddress['mas_village_id'];
        $empPermenantAddress->thram_no = $permenantAddress['thram_no'];
        $empPermenantAddress->house_no = $permenantAddress['house_no'];
        $empPermenantAddress->save();

        $empCurrentAddress->mas_employee_id = $employeeId;
        $empCurrentAddress->mas_dzongkhag_id = $currentAddress['mas_dzongkhag_id'];
        $empCurrentAddress->mas_gewog_id = $currentAddress['mas_gewog_id'];
        $empCurrentAddress->city = $currentAddress['city'];
        $empCurrentAddress->postal_code = $currentAddress['postal_code'];
        $empCurrentAddress->save();
    }

    private function saveJob($job, $employeeId, $request)
    {
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            $empJob = MasEmployeeJob::where('mas_employee_id', $employeeId)->firstOrFail();
        } else {
            $empJob = new MasEmployeeJob();
        }

        $empJob->mas_employee_id = $employeeId;
        $empJob->mas_department_id = $job['mas_department_id'];
        $empJob->mas_section_id = $job['mas_section_id'];
        $empJob->mas_designation_id = $job['mas_designation_id'];
        $empJob->mas_grade_id = $job['mas_grade_id'];
        $empJob->mas_grade_step_id = $job['mas_grade_step_id'];
        $empJob->mas_employment_type_id = $job['mas_employment_type_id'];
        $empJob->immediate_supervisor = $job['immediate_supervisor'] ?? null;
        $empJob->mas_office_id = $job['mas_office_id'];
        $empJob->basic_pay = $job['basic_pay'];
        $empJob->bank = $job['bank'];
        $empJob->account_number = $job['account_number'];
        $empJob->pf_number = $job['pf_number'];
        $empJob->tpn_number = $job['tpn_number'];
        $empJob->save();
    }

    private function saveQualifications($qualifications, $employeeId, $request)
    {
        // Fetch all existing qualifications for the employee, keyed by mas_qualification_id
        $existingQualifications = MasEmployeeQualification::where('mas_employee_id', $employeeId)
            ->get()
            ->keyBy('mas_qualification_id');

        $qualificationIdsInRequest = []; // Track IDs from the request

        foreach ($qualifications as $key => $value) {
            $qualificationId = $value['mas_qualification_id'];
            $qualificationIdsInRequest[] = $qualificationId;

            if (isset($existingQualifications[$qualificationId])) {
                // Update existing record
                MasEmployeeQualification::where('mas_employee_id', $employeeId)
                    ->where('mas_qualification_id', $qualificationId)
                    ->update([
                        'school' => $value['school'],
                        'subject' => $value['subject'],
                        'completion_year' => $value['completion_year'],
                        'aggregate_score' => $value['aggregate_score'],
                    ]);
            } else {
                // Insert new record
                MasEmployeeQualification::create([
                    'mas_employee_id' => $employeeId,
                    'mas_qualification_id' => $qualificationId,
                    'school' => $value['school'],
                    'subject' => $value['subject'],
                    'completion_year' => $value['completion_year'],
                    'aggregate_score' => $value['aggregate_score'],
                ]);
            }
        }

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            // Delete any qualifications that are not in the current request
            MasEmployeeQualification::where('mas_employee_id', $employeeId)
                ->whereNotIn('mas_qualification_id', $qualificationIdsInRequest)
                ->delete();
        }
    }

    private function saveTrainings($trainings, $employeeId, $request)
    {
        // Fetch all existing trainings for the employee, keyed by id
        $existingTrainings = MasEmployeeTraining::where('mas_employee_id', $employeeId)
            ->get()
            ->keyBy('id');

        $trainingIdsInRequest = []; // Track IDs from the request

        foreach ($trainings as $key => $value) {
            $trainingId = $value['id'] ?? null; // Assume the form might include an id for existing records
            $trainingCertificate = isset($value['certificate']) ? uploadImageToDirectory($value['certificate'], $this->filePath) : null;

            if ($trainingId && isset($existingTrainings[$trainingId])) {
                // Update existing record
                $existingTrainings[$trainingId]->update([
                    'title' => $value['title'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'duration' => $value['duration'],
                    'location' => $value['location'],
                    'description' => $value['description'],
                    'certificate' => $trainingCertificate ?? null,
                ]);
            } else {
                // Insert new record
                $newTraining = MasEmployeeTraining::create([
                    'mas_employee_id' => $employeeId,
                    'title' => $value['title'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'duration' => $value['duration'],
                    'location' => $value['location'],
                    'description' => $value['description'],
                    'certificate' => $trainingCertificate ?? null,
                ]);
                $trainingIdsInRequest[] = $newTraining->id; // Track new ID
            }

            if ($trainingId) {
                $trainingIdsInRequest[] = $trainingId;
            }
        }

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            // Delete any trainings that are not in the current request
            MasEmployeeTraining::where('mas_employee_id', $employeeId)
                ->whereNotIn('id', $trainingIdsInRequest)
                ->delete();
        }
    }

    private function saveExperiences($experiences, $employeeId, $request)
    {
        // Fetch all existing experiences for the employee, keyed by their IDs
        $existingExperiences = MasEmployeeExperience::where('mas_employee_id', $employeeId)
            ->get()
            ->keyBy('id');

        $experienceIdsInRequest = []; // Track IDs from the request

        foreach ($experiences as $value) {
            $experienceId = $value['id'] ?? null; // Use null if 'id' is not set

            if ($experienceId && isset($existingExperiences[$experienceId])) {
                // Update existing record
                $existingExperiences[$experienceId]->update([
                    'organization' => $value['organization'],
                    'place' => $value['place'],
                    'designation' => $value['designation'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'description' => $value['description'],
                ]);

                $experienceIdsInRequest[] = $experienceId; // Add to tracking array
            } else {
                // Insert new record
                $newExperience = MasEmployeeExperience::create([
                    'mas_employee_id' => $employeeId,
                    'organization' => $value['organization'],
                    'place' => $value['place'],
                    'designation' => $value['designation'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'description' => $value['description'],
                ]);

                $experienceIdsInRequest[] = $newExperience->id; // Track the new ID
            }
        }

        if ($request->isMethod('put') || $request->isMethod('patch')) {
            // Delete any experiences that are not in the current request
            MasEmployeeExperience::where('mas_employee_id', $employeeId)
                ->whereNotIn('id', $experienceIdsInRequest)
                ->delete();
        }
    }


    private function saveDocuments($doc, $employeeId, $request)
    {

        $empContract = "";
        $empNonDisclosureAggrement = "";
        $jobResponsibilities = "";
        $otherDocuments = [];
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            $empDocument = MasEmployeeDocument::where('mas_employee_id', $employeeId)->firstOrFail();

            if (isset($doc['employment_contract'])) {
                $empContract = uploadImageToDirectory($doc['employment_contract'], $this->filePath);
            } else {
                $empContract = $empDocument->employment_contract;
            }
            if (isset($doc['non_disclosure_aggrement'])) {
                $empNonDisclosureAggrement = uploadImageToDirectory($doc['non_disclosure_aggrement'], $this->filePath);
            } else {
                $$empNonDisclosureAggrement  = $empDocument->non_disclosure_aggrement;
            }
            if (isset($doc['job_responsibilities'])) {
                $jobResponsibilities = uploadImageToDirectory($doc['job_responsibilities'], $this->filePath);
            } else {
                $jobResponsibilities = $empDocument->job_responsibilities;
            }

            if ($empDocument->other) { //if it has existing document in table then assign existingDocuments to otherDocuments
                $existingDocuments = json_decode($empDocument->other);
                $otherDocuments = $existingDocuments;
            }

            if (isset($doc['other'])) { //if there is other dcument from request
                foreach ($doc['other'] as $otherFile) {
                    $otherDocuments[] = uploadImageToDirectory($otherFile, $this->filePath);
                }
            }
        } else {
            $empDocument = new MasEmployeeDocument();
            if (isset($doc['employment_contract'])) {
                $empContract = uploadImageToDirectory($doc['employment_contract'], $this->filePath);
            } else {
                throw new \Exception('Please upload employment contract document.');
            }
            if (isset($doc['non_disclosure_aggrement'])) {
                $empNonDisclosureAggrement = uploadImageToDirectory($doc['non_disclosure_aggrement'], $this->filePath);
            } else {
                throw new \Exception('Please upload employee non disclosure aggrement.');
            }
            if (isset($doc['job_responsibilities'])) {
                $jobResponsibilities = uploadImageToDirectory($doc['job_responsibilities'], $this->filePath);
            } else {
                throw new \Exception('Please upload employee job responsibilities.');
            }
            if (isset($doc['other'])) {
                foreach ($doc['other'] as $otherFile) {
                    $otherDocuments[] = uploadImageToDirectory($otherFile, $this->filePath);
                }
            }
        }

        $empDocument->mas_employee_id = $employeeId;
        $empDocument->employment_contract = $empContract;
        $empDocument->non_disclosure_aggrement = $empNonDisclosureAggrement;
        $empDocument->job_responsibilities = $jobResponsibilities;
        $empDocument->other = empty($otherDocuments) ? null : json_encode($otherDocuments);
        $empDocument->save();
    }

    private function fetchHighestEmpId()
    {
        return User::max('employee_id');
    }
}
