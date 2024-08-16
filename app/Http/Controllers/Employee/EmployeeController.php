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
        $this->middleware('permission:employee/employee-lists,view-detail')->only('detail');
    }

    private $filePath = 'images/employee/';

    protected $rules = [

    ];

    protected $messages = [

    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $users = User::filter($request)->orderBy('name')->paginate(config('global.pagination'))->withQueryString();
        
        return view('employee/employee-list.index',compact('privileges', 'users'));
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
        try{
            $employeeId = $this->savePersonalInfo($request->personal);
            $this->saveAddress($request->permenant_address, $request->current_address, $employeeId);
            $this->saveJob($request->job, $employeeId);
            $this->saveQualifications($request->qualifications, $employeeId);
            $this->saveTrainings($request->trainings, $employeeId);
            $this->saveExperiences($request->experiences, $employeeId);
            $this->saveDocuments($request->documents, $employeeId);

            DB::commit();
        }catch(\Exception $e){
            // dd($e);
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', 'Employee couldnot be created, please try again.');
        }
        return redirect('employee/employee-lists')->with('msg_success', 'Employee created successfully.');
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
        $employee = User::findOrFail($id);

        return view('masters.village.edit', compact( 'employee'));
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
        // dd($personalInfo);
        $user = new User();
        $profilePic = isset($personalInfo['profile_pic']) ? uploadImageToDirectory($personalInfo['profile_pic'], 'images/users/') : null;
        $empCidCopy = "";
        if(isset($personalInfo['cid_copy'])){
            $file = $personalInfo['cid_copy'];
            $empCidCopy = uploadImageToDirectory($file, $this->filePath);
        }else{
            throw new \Exception('Please upload the employee CID copy.');    
        }
        
        $name = trim($personalInfo['first_name'] . ($personalInfo['middle_name'] ? ' ' . $personalInfo['middle_name'] : '') . ($personalInfo['last_name'] ? ' ' . $personalInfo['last_name'] : ''));
        
        $user->first_name = $personalInfo['first_name'];
        $user->middle_name = $personalInfo['middle_name'];
        $user->last_name = $personalInfo['last_name'];
        $user->title = $personalInfo['title'];
        $user->name = $name;
        $user->username = $personalInfo['username'];
        $user->password = bcrypt('password');
        $user->email = $personalInfo['email'];
        $user->cid_no = $personalInfo['cid_no'];
        $user->employee_id = $personalInfo['employee_id'];
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
        if($permenantAddress){
            $empPermenantAddress->mas_employee_id = $employeeId;
            $empPermenantAddress->mas_dzongkhag_id = $permenantAddress['mas_dzongkhag_id'];
            $empPermenantAddress->mas_gewog_id = $permenantAddress['mas_gewog_id'];
            $empPermenantAddress->mas_village_id = $permenantAddress['mas_village_id'];
            $empPermenantAddress->thram_no = $permenantAddress['thram_no'];
            $empPermenantAddress->house_no = $permenantAddress['house_no'];
            $empPermenantAddress->save();
        }
        if($currentAddress){
            $empCurrentAddress->mas_employee_id = $employeeId;
            $empCurrentAddress->mas_dzongkhag_id = $currentAddress['mas_dzongkhag_id'];
            $empCurrentAddress->mas_gewog_id = $currentAddress['mas_gewog_id'];
            $empCurrentAddress->city = $currentAddress['city'];
            $empCurrentAddress->postal_code = $currentAddress['postal_code'];
            $empCurrentAddress->save();
        }

    }

    private function saveJob($job, $employeeId){
        $empJob = new MasEmployeeJob();
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
        $empJob->bank = $job['bank'];
        $empJob->account_number = $job['account_number'];
        $empJob->pf_number = $job['pf_number'];
        $empJob->tpn_number = $job['tpn_number'];
        $empJob->save();
    }

    private function saveQualifications($qualifications, $employeeId){
        $qualificationData = [];
        foreach($qualifications as $key => $value){
            $qualification[] = [
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
            $training[] = [
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
            $experience[] = [
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
        // $user = new User();
        // $user->id = $employeeId;
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

        // dd($otherDocuments);
        $EmpDocument->mas_employee_id = $employeeId;
        $EmpDocument->employment_contract = $empContract;
        $EmpDocument->non_disclosure_aggrement = $empNonDisclosureAggrement;
        $EmpDocument->job_responsibilities = $jobResponsibilities;
        $EmpDocument->other = empty($otherDocuments) ? null : json_encode($otherDocuments);
        $EmpDocument->save();
    }
}
