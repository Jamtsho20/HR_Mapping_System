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
        $this->middleware('permission:employee/employee-lists,view')->only('index');
        $this->middleware('permission:employee/employee-lists,create')->only('store');
        $this->middleware('permission:employee/employee-lists,edit')->only('update');
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
        'personal.cid_copy' => 'required|file|mimes:jpg,png,pdf|max:2048',
        //permenat address validation rule
        'permenant_address.mas_dzongkhag_id' => 'required', 
        'permenant_address.mas_gewog_id' => 'required', 
        'permenant_address.mas_village_id' => 'required', 
        'permenant_address.thram_no' => 'required', 
        'permenant_address.house_no' => 'required', 
        //present address validation rule
        'current_address.mas_dzongkhag_id' => 'required', 
        'current_address.mas_gewog_id' => 'required', 
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
        'documents.employment_contract' => 'required|file|mimes:jpg,png,pdf|max:2048',
        'documents.non_disclosure_aggrement' => 'required|file|mimes:jpg,png,pdf|max:2048',
        'documents.job_responsibilities' => 'required|file|mimes:jpg,png,pdf|max:2048',
    ];

    protected $messages = [];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employees = User::filter($request)->orderBy('name')->paginate(config('global.pagination'))->withQueryString();
        
        return view('employee/employee-list.index',compact('privileges', 'employees'));
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
        $employeeId = fixEmployeeId($this->fetchHighestEmpId() + 1);
        return view('employee/employee-list.create', compact('dzongkhags', 'gewogs', 'departments', 'designations', 'grades', 'employmentTypes', 'qualifications', 'employeeId'));
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
            if(!empty($request->qualifications)){
                $this->saveQualifications($request->qualifications, $employeeId);
            }
            if(!empty($request->trainings)){
                $this->saveTrainings($request->trainings, $employeeId);
            }
            if(!empty($request->experiences)){
                $this->saveExperiences($request->experiences, $employeeId);
            }
            $this->saveDocuments($request->documents, $employeeId);

            DB::commit();
        }catch(\Exception $e){
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
        // dd($employee->empJob->supervisor);
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

    private function savePersonalInfo($personalInfo){
        $user = new User();
        $employeeId = $this->fetchHighestEmpId() + 1;
        $userName = fixEmployeeId($employeeId);
        $profilePic = isset($personalInfo['profile_pic']) ? uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/') : null;
        $empCidCopy = "";
        if (isset($personalInfo['cid_copy'])) {
            $file = $personalInfo['cid_copy'];
            $empCidCopy = uploadImageToDirectory($file, $this->filePath);
        }else{
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

        return $user->id;
    }

    private function saveAddress($permenantAddress, $currentAddress, $employeeId){
        $empPermenantAddress = new MasEmployeePermenantAddress();
        $empCurrentAddress = new MasEmployeePresentAddress();
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

    private function saveJob($job, $employeeId){
        $empJob = new MasEmployeeJob();
        // $basicPay = MasGradeStep::where('id', $job['mas_grade_step_id'])->pluck('starting_salary')->first();
        $empJob->mas_employee_id = $employeeId;
        $empJob->mas_department_id = $job['mas_department_id'];
        $empJob->mas_section_id = $job['mas_section_id'];
        $empJob->mas_designation_id = $job['mas_designation_id'];
        $empJob->mas_grade_id = $job['mas_grade_id'];
        $empJob->mas_grade_step_id = $job['mas_grade_step_id'];
        $empJob->mas_employment_type_id = $job['mas_employment_type_id'];
        $empJob->immediate_supervisor = $job['immediate_supervisor'] ?? null;
        $empJob->job_location = $job['job_location'];
        $empJob->basic_pay = $job['basic_pay'];
        // $empJob->basic_pay = $basicPay;
        $empJob->bank = $job['bank'];
        $empJob->account_number = $job['account_number'];
        $empJob->pf_number = $job['pf_number'];
        $empJob->tpn_number = $job['tpn_number'];
        $empJob->save();
    }

    private function saveQualifications($qualifications, $employeeId){
        $qualificationData = [];
        foreach($qualifications as $key => $value){
            $qualificationData[] = [
                'mas_employee_id' => $employeeId,
                'mas_qualification_id' => $value['mas_qualification_id'],
                'school' => $value['school'],
                'subject' => $value['subject'],
                'completion_year' => $value['completion_year'],
                'aggregate_score' => $value['aggregate_score'],
            ];
        }
        DB::table('mas_employee_qualifications')->insert($qualificationData);
    }

    private function saveTrainings($trainings, $employeeId){
        $trainingData = [];
        foreach($trainings as $key => $value){
            $trainingCertificate = isset($value['certificate']) ? uploadImageToDirectory($value['certificate'], $this->filePath) : null;
            $trainingData[] = [
                'mas_employee_id' => $employeeId,
                'title' => $value['title'],
                'start_date' => $value['start_date'],
                'end_date' => $value['end_date'],
                'duration' => $value['duration'],
                'location' => $value['location'],
                'description' => $value['description'],
                'certificate' => $trainingCertificate ?? null, //check if file exists and save the path
            ];
        } 
        DB::table('mas_employee_trainings')->insert($trainingData);
    }

    private function saveExperiences($experiences, $employeeId){
        $experienceData = [];
        foreach($experiences as $key => $value){
            $experienceData[] = [
                'mas_employee_id' => $employeeId,
                'organization' => $value['organization'],
                'place' => $value['place'],
                'designation' => $value['designation'],
                'start_date' => $value['start_date'],
                'end_date' => $value['end_date'],
                'description' => $value['description'],
            ];
        }
        DB::table('mas_employee_experiences')->insert($experienceData);
    }

    private function saveDocuments($doc, $employeeId){
        $EmpDocument = new MasEmployeeDocument();
        $empContract = "";
        $empNonDisclosureAggrement = "";
        $jobResponsibilities = "";
        $otherDocuments = [];
        if(isset($doc['employment_contract'])){
            $empContract = uploadImageToDirectory($doc['employment_contract'], $this->filePath);
        }else{
            throw new \Exception('Please upload employment contract document.');
        }
        if(isset($doc['non_disclosure_aggrement'])){
            $empNonDisclosureAggrement = uploadImageToDirectory($doc['non_disclosure_aggrement'], $this->filePath);
        }else{
            throw new \Exception('Please upload employee non disclosure aggrement.');
        }
        if(isset($doc['job_responsibilities'])){
            $jobResponsibilities = uploadImageToDirectory($doc['job_responsibilities'], $this->filePath);
        }else{
            throw new \Exception('Please upload employee job responsibilities.');
        }
        // dd($doc['other']);
        if(isset($doc['other'])){
            foreach($doc['other'] as $otherFile){
                $otherDocuments[] = uploadImageToDirectory($otherFile, $this->filePath);
            }
        }

        $EmpDocument->mas_employee_id = $employeeId;
        $EmpDocument->employment_contract = $empContract;
        $EmpDocument->non_disclosure_aggrement = $empNonDisclosureAggrement;
        $EmpDocument->job_responsibilities = $jobResponsibilities;
        $EmpDocument->other = empty($otherDocuments) ? null : json_encode($otherDocuments);
        $EmpDocument->save();
    }

    private function fetchHighestEmpId(){
        return User::max('employee_id');
    }
}
