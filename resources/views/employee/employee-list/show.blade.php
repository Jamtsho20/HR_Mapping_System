@extends('layouts.app')
@section('page-title', 'Showing Employee Details')
@section('buttons')
<a href="{{ url('employee/employee-lists/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Employee List</a>
@endsection
@section('content')
<div class="row">
    <!-- Personal Details -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$employee['name'] }}
                    <span class="badge rounded-pill  bg-{{$employee['is_active'] == 1 ? 'primary' : 'danger' }} me-1 mt-1">{{$employee['is_active']== 1 ? 'active':'inactive'}}
                </h3>

            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Username</b> <a class="pull-right">{{$employee['username'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>DOB</b> <a class="pull-right">{{$employee['dob'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Employee ID</b> <a class="pull-right">{{$employee ['employee_id'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Contact No</b> <a class="pull-right">{{$employee ['contact_number'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="pull-right">{{$employee ['email'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>CID</b> <a class="pull-right">{{$employee ['cid_no'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gender</b>
                        @if($employee ['gender']==1)
                        <a class="pull-right"> Male</a>
                        @elseif ($employee ['gender']==2)
                        <a class="pull-right"> Female</a>
                        @else
                        <a class="pull-right"> Other</a>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>Marital Status</b> @if($employee['marital_status']==1)
                        <a class="pull-right"> Single</a>
                        @elseif ($employee['marital_status']==2)
                        <a class="pull-right"> Married</a>
                        @else
                        <a class="pull-right"> Divorced</a>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>DOJ</b> <a class="pull-right">{{$employee ['date_of_appointment'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Nationality</b> <a class="pull-right">{{$employee['nationality'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Birth Place</b> <a class="pull-right">{{$employee['birth_place'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Birth Country</b> <a class="pull-right">{{$employee ['birth_country'] }}</a>
                    </li>
                </ul>
            </div>
            @if ($canUpdate === 1)
            <div class="card-footer">
                <a href="{{ url('employee/employee-lists/' .$employee['id'] . '/edit') }}" class="btn btn-outline-primary btn-block btn-sm"><b><i class="fa fa-edit"></i> Edit Record</b></a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <!-- Qualification -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Qualification(s)

                </h3>

            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Qualification</b> <a class="pull-right">{{$employee['emp_qualifications'][0]['mas_qualification']['name'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>School</b> <a class="pull-right">{{$employee['emp_qualifications'][0]['school'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Subject</b> <a class="pull-right">{{$employee['emp_qualifications'][0]['subject'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Completion Year</b> <a class="pull-right">{{$employee['emp_qualifications'][0]['completion_year']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Aggregate Score</b> <a class="pull-right">{{$employee['emp_qualifications'][0]['aggregate_score'] }}</a>
                    </li>


                </ul>
            </div>

        </div>
        <!-- Experiences -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Experience(s)
                </h3>

            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Organization</b> <a class="pull-right">{{$employee['emp_experiences'][0]['organization'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Place</b> <a class="pull-right">{{$employee['emp_experiences'][0]['place'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Designation</b> <a class="pull-right">{{$employee['emp_experiences'][0]['designation'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Description</b> <a class="pull-right">{{$employee['emp_experiences'][0]['description'] }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Start Date</b> <a class="pull-right">{{$employee['emp_experiences'][0]['start_date']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>End Date</b> <a class="pull-right">{{$employee['emp_experiences'][0]['end_date'] }}</a>
                    </li>


                </ul>
            </div>

        </div>
    </div>
    <!-- Address -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Present Address(s)</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Dzongkhag</b> <a class="pull-right">{{$employee ['emp_present_address'] ['mas_dzongkhag']['dzongkhag']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gewog</b> <a class="pull-right">
                            {{ $employee['emp_present_address']['mas_dzongkhag']['gewogs'][0]['name'] ?? 'No Gewog' }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>City</b> <a class="pull-right">
                            {{ $employee['emp_present_address']['city'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Postal Code</b> <a class="pull-right">
                            {{ $employee['emp_present_address']['postal_code'] }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card ">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Permanent Address(s)</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Dzongkhag</b> <a class="pull-right">{{$employee ['emp_permenant_address'] ['mas_dzongkhag']['dzongkhag']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gewog</b> <a class="pull-right">
                            {{ $employee['emp_permenant_address']['mas_dzongkhag']['gewogs'][0]['name'] ?? 'No Gewog' }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Tharm No</b> <a class="pull-right">
                            {{ $employee['emp_permenant_address']['thram_no'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>House No</b> <a class="pull-right">
                            {{ $employee['emp_permenant_address']['house_no'] }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Jobs -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Job Detail(s)</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Designation</b> <a class="pull-right">{{$employee ['emp_job'] ['mas_designation']['name']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Grade</b> <a class="pull-right">
                            {{ $employee ['emp_job'] ['mas_grade']['name'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Grade Step</b> <a class="pull-right">
                            {{$employee ['emp_job'] ['mas_grade_step']['name'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Department</b> <a class="pull-right">
                            {{ $employee ['emp_job'] ['mas_department']['name'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Section</b> <a class="pull-right">
                            {{ $employee ['emp_job'] ['mas_department']['sections'][0]['name'] }}
                        </a>
                    </li>

                </ul>
            </div>
        </div>

    </div>



</div>
@endsection