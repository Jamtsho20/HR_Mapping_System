<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasDesignation;
use Illuminate\Http\Request;
use App\Models\MasDzongkhag;
use App\Models\MasEmployeeDocument;
use App\Models\MasEmployeeJob;
use App\Models\MasEmployeePermenantAddress;
use App\Models\MasEmployeePresentAddress;
use App\Models\MasEmploymentType;
use App\Models\MasGewog;
use App\Models\MasGrade;
use App\Models\MasQualification;
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
        $this->middleware('permission:employee/employee-lists,edit')->only('update');
        $this->middleware('permission:employee/employee-lists,delete')->only('destroy');
    }

    protected $rules = [];

    protected $messages = [];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employees = User::all();

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
        $designations = MasDesignation::orderBy('name')->get(['id', 'name']);
        $grades = MasGrade::orderBy('name')->get(['id', 'name']);
        $employmentTypes = MasEmploymentType::orderBy('name')->get(['id', 'name']);
        $qualifications = MasQualification::orderBy('name')->get(['id', 'name']);

        return view('employee/employee-list.create', compact('dzongkhags', 'gewogs', 'departments', 'designations', 'grades', 'employmentTypes', 'qualifications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
        DB::beginTransaction();
        try {
            $employeeId = $this->savePersonalInfo($request->personal);
            $this->saveAddress($request->permenant_address, $request->current_address, $employeeId);
            $this->saveJob($request->job, $employeeId);
            $this->saveQualifications($request->qualifications, $employeeId);
            $this->saveTrainings($request->trainings, $employeeId);
            $this->saveExperiences($request->experiences, $employeeId);
            $this->saveDocuments($request->documents, $employeeId);

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->with('msg_error', 'The employee could not be created, please try again.');
        }
        return redirect('employee/employee-lists')->with('msg_success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $instance = $request->instance(); //we need to pull the privileges from the instance
        $canUpdate = (int) $instance->edit;
        $employee = User::with('empPresentAddress.masDzongkhag', 'empPresentAddress.masDzongkhag.gewogs', 'empPermenantAddress.masDzongkhag', 'empPermenantAddress.masDzongkhag.gewogs', 'empQualifications.masQualification', 'empExperiences', 'empJob.masDepartment.sections', 'empJob.masDesignation', 'empJob.masGrade', 'empJob.masGradeStep')->findOrFail($id)->toArray();
        // dd($employee);

        return view('employee.employee-list.show', compact('employee', 'canUpdate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = User::findOrFail($id);

        return view('masters.village.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $this->validate($request, $this->rules, $this->messages);
        // DB::beginTransaction();
        // try{
        //     $employeeId = $this->savePersonalInfo($request->personal);
        //     $this->saveAddress($request->permenant_address, $request->current_address, $employeeId);
        //     $this->saveJob($request->job, $employeeId);
        //     $this->saveQualifications($request->qualifications);
        //     $this->saveTrainings($request->trainings);
        //     $this->saveExperiences($request->experiences);
        //     $this->saveDocuments($request->documents);

        //     DB::commit();
        // }catch(\Exception $e){
        //     DB::rollBack();
        //     return back()->with('msg_error', 'The employee could not be updated, please try again.');
        // }
        // return redirect('employee/employee-lists')->with('msg_success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // try {
        //     User::findOrFail($id)->delete();

        //     return back()->with('msg_success', 'The qualification has been deleted successfully');
        // } catch(\Exception $exception){
        //     return back()->with('msg_error', 'The qualification cannot be deleted as it has been used and is linked with other modules. For further information contact system admin.');
        // }
    }

    private function savePersonalInfo($personalInfo)
    {
        $user = new User();
        $profilePic = isset($personalInfo['profile_pic']) ? uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/') : null;
        $empCidCopy = "";
        if (isset($personalInfo['cid_copy'])) {
            $file = $personalInfo['cid_copy'];
            $empCidCopy = uploadImageToDirectory($file, 'images/emp-cid-copy/');
        } else {
            return back()->withInput()->with('msg_error', 'Please upload the employee cid copy.');
        }

        $name = trim($personalInfo['first_name'] . ($personalInfo['middle_name'] ? ' ' . $personalInfo['middle_name'] : '') . ($personalInfo['last_name'] ? ' ' . $personalInfo['last_name'] : ''));

        $userName = 'test';
        $user->first_name = $personalInfo['first_name'];
        $user->middle_name = $personalInfo['middle_name'];
        $user->last_name = $personalInfo['last_name'];
        $user->title = $personalInfo['title'];
        $user->name = $name;
        $user->username = $userName;
        $user->password = bcrypt('password');
        $user->email = $personalInfo['email'];
        $user->cid_no = $personalInfo['cid_no'];
        $user->employee_id = $personalInfo['employee_id'];
        $user->gender = $personalInfo['gender'];
        $user->dob = $personalInfo['dob'];
        $user->birth_place = $personalInfo['birth_place'];
        $user->birth_country = $personalInfo['birth_country'];
        $user->marital_status = $personalInfo['marital_status'];
        $user->email = $personalInfo['email'];
        $user->contact_number = $personalInfo['contact_number'];
        $user->nationality = $personalInfo['nationality'];
        $user->date_of_appointment = $personalInfo['date_of_appointment'];
        $user->is_active = $personalInfo['is_active'];
        $user->profile_pic = $profilePic;
        $user->cid_copy = $empCidCopy;
        $user->save();

        return $user->id;
    }

    private function saveAddress($permenantAddress, $currentAddress, $employeeId)
    {
        $user = new User();
        $user->id = $employeeId;
        if ($permenantAddress) {
            $user->empPermenantAddress()->create([
                'mas_employee_id' => $user->id,
                'mas_dzongkhag_id' => $permenantAddress['mas_dzongkhag_id'],
                'mas_gewog_id' => $permenantAddress['mas_gewog_id'],
                'mas_village_id' => $permenantAddress['mas_village_id'],
                'thram_no' => $permenantAddress['thram_no'],
                'house_no' => $permenantAddress['house_no']
            ]);
        }
        if ($currentAddress) {
            $user->empPresentAddress()->create([
                'mas_employee_id' => $user->id,
                'mas_dzongkhag_id' => $currentAddress['mas_dzongkhag_id'],
                'mas_gewog_id' => $currentAddress['mas_gewog_id'],
                'city' => $currentAddress['city'],
                'postal_code' => $currentAddress['postal_code']
            ]);
        }
    }

    private function saveJob($job, $employeeId)
    {
        $user = new User();
        $user->id = $employeeId;
        $user->empJob()->create([
            'mas_employee_id' => $user->id,
            'mas_department_id' => $job['mas_department_id'],
            'mas_section_id' => $job['mas_section_id'],
            'mas_designation_id' => $job['mas_designation_id'],
            'mas_grade_id' => $job['mas_grade_id'],
            'mas_grade_step_id' => $job['mas_grade_step_id']
        ]);
    }

    private function saveQualifications($qualifications, $employeeId)
    {
        $user = new User();
        $user->id = $employeeId;
        $qualification = [];
        foreach ($qualifications as $key => $value) {
            $qualification[] = [
                'mas_employee_id' => $user->id,
                'mas_qualification_id' => $value['mas_qualification_id'],
                'school' => $value['school'],
                'subject' => $value['subject'],
                'completion_year' => $value['completion_year'],
                'aggregate_score' => $value['aggregate_score'],
            ];
        }
        $user->empQualifications()->createMany($qualification);
    }

    private function saveTrainings($trainings, $employeeId)
    {
        $user = new User();
        $user->id = $employeeId;
        $training = [];
        foreach ($trainings as $key => $value) {
            $trainingCertificate = $value['certificate'] ? uploadImageToDirectory($value['certificate'], '/images/emp-training-cert') : null;
            $training[] = [
                'mas_employee_id' => $user->id,
                'title' => $value['title'],
                'start_date' => $value['start_date'],
                'end_date' => $value['end_date'],
                'duration' => $value['duration'],
                'location' => $value['location'],
                'description' => $value['description'],
                'certificate' => $trainingCertificate, //check if file exists and save the path
            ];
        }
        $user->empTrainings()->createMany($training);
    }

    private function saveExperiences($experiences, $employeeId)
    {
        // $empExperience = new MasEmployeeExperience();
        $user = new User();
        $user->id = $employeeId;
        $experience = [];
        foreach ($experiences as $key => $value) {
            $experience[] = [
                'mas_employee_id' => $user->id,
                'organization' => $value['organization'],
                'place' => $value['place'],
                'designation' => $value['designation'],
                'start_date' => $value['start_date'],
                'end_date' => $value['end_date'],
                'description' => $value['description'],
            ];
        }
        $user->empExperiences()->createMany($experience);
    }

    private function saveDocuments($doc, $employeeId)
    {
        $user = new User();
        $user->id = $employeeId;
        $empContract = "";
        $empNonDisclosureAggrement = "";
        $jobResponsibilities = "";
        $otherDocuments = [];
        if (isset($doc['employment_contract'])) {
            $empContract = uploadImageToDirectory($doc['employment_contract'], 'images/emp-doc/');
        } else {
            return back()->withInput()->with('msg_error', 'Please upload employment contract document.');
        }
        if (isset($doc['non_disclosure_aggrement'])) {
            $empNonDisclosureAggrement = uploadImageToDirectory($doc['non_disclosure_aggrement'], 'images/emp-doc/');
        } else {
            return back()->withInput()->with('msg_error', 'Please upload employee non disclosure aggrement.');
        }
        if (isset($doc['job_responsibilities'])) {
            $jobResponsibilities = uploadImageToDirectory($doc['job_responsibilities'], 'images/emp-doc/');
        } else {
            return back()->withInput()->with('msg_error', 'Please upload employee job responsibilities.');
        }
        if (isset($doc['other'])) {
            foreach ($doc['other'] as $otherFile) {
                $otherDocuments[] = uploadImageToDirectory($otherFile, 'images/emp-doc/');
            }
        }
        $otherDocumentString = implode(',', $otherDocuments);
        $user->empDoc()->create([
            'mas_employee_id' => $user->id,
            'employment_contract' => $empContract,
            'non_disclosure_aggrement' => $empNonDisclosureAggrement,
            'job_responsibilities' => $jobResponsibilities,
            'other' => $otherDocumentString
        ]);
    }
}
