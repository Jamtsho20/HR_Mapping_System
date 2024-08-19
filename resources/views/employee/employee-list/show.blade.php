@extends('layouts.app')
@section('page-title', 'Showing Employee Details')
@section('buttons')
<a href="{{ url('employee/employee-lists/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Employee List</a>
@endsection
@section('content')
<div class="row">
    <!-- Personal Details -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$employee->name }} ({{ $employee->username }})
                    <span class="badge rounded-pill  bg-{{$employee->is_active == 1 ? 'primary' : 'danger' }} me-1 mt-1">{{$employee->is_active == 1 ? 'active':'inactive'}}
                </h3>

            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>DOB</b> <a class="pull-right">{{ $employee->dob }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Contact No</b> <a class="pull-right">{{ $employee->contact_number }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email Id</b> <a class="pull-right">{{ $employee->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>CID</b> <a class="pull-right">{{ $employee->cid_no }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gender</b>
                        @if($employee->gender==1)
                        <a class="pull-right"> Male</a>
                        @elseif ($employee->gender == 2)
                        <a class="pull-right"> Female</a>
                        @else
                        <a class="pull-right"> Other</a>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>Marital Status</b> @if($employee->marital_status == 1)
                        <a class="pull-right"> Single</a>
                        @elseif ($employee->marital_status == 2)
                        <a class="pull-right"> Married</a>
                        @else
                        <a class="pull-right"> Divorced</a>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>DOJ</b> <a class="pull-right">{{ $employee->date_of_appointment }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Nationality</b> <a class="pull-right">{{ $employee->nationality }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Birth Place</b> <a class="pull-right">{{ $employee->birth_place }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Birth Country</b> <a class="pull-right">{{ $employee->birth_country }}</a>
                    </li>
                </ul>
            </div>
            @if ($canUpdate === 1)
            <div class="card-footer">
                <a href="{{ url('employee/employee-lists/' .$employee->id . '/edit') }}" class="btn btn-outline-primary btn-block btn-sm"><b><i class="fa fa-edit"></i> Edit Record</b></a>
            </div>
            @endif
        </div>
    </div>
    <!-- Address -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Present Address(s)</h3>
                <button type="button" class="btn btn-tool place" data-toggle="collapse" data-target="#experience-card-body">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <div id="#experience-card-body" class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Dzongkhag</b> <a class="pull-right">{{ $employee->empPresentAddress->masDzongkhag->dzongkhag }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gewog</b> <a class="pull-right">
                            {{ $employee->empPresentAddress->masGewog->name }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>City</b> <a class="pull-right">
                            {{ $employee->empPresentAddress->city }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Postal Code</b> <a class="pull-right">
                            {{ $employee->empPresentAddress->postal_code }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Permanent Address(s)</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Dzongkhag</b> <a class="pull-right">{{ $employee->empPermenantAddress->masDzongkhag->dzongkhag }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gewog</b> <a class="pull-right">
                            {{ $employee->empPermenantAddress->masGewog->name }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Tharm No</b> <a class="pull-right">
                            {{ $employee->empPermenantAddress->thram_no }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>House No</b> <a class="pull-right">
                            {{ $employee->empPermenantAddress->house_no }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Employee Job related -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Job Details</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Department</b> <a class="pull-right">{{ $employee->empJob->department->code_name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Section</b> <a class="pull-right">{{ $employee->empJob->section->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Designation</b> <a class="pull-right">{{ $employee->empJob->designation->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Grade</b> <a class="pull-right">{{ $employee->empJob->grade->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Grade Step</b> <a class="pull-right">{{ $employee->empJob->gradeStep->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Employment Type</b> <a class="pull-right">{{ $employee->empJob->empType->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Supervisor</b> <a class="pull-right">{{ $employee->empJob->supervisor->emp_id_name ?? config('global.null_value') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Job Location</b> <a class="pull-right">{{ $employee->empJob->job_location }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Pay Scale</b> <a class="pull-right">{{ $employee->empJob->gradeStep->pay_scale }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Basic Pay</b> <a class="pull-right">{{ $employee->empJob->basic_pay }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Bank</b> <a class="pull-right">{{ $employee->empJob->bank }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Account Number</b> <a class="pull-right">{{ $employee->empJob->account_number }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>PF Number</b> <a class="pull-right">{{ $employee->empJob->pf_number }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>TPN Number</b> <a class="pull-right">{{ $employee->empJob->tpn_number }}</a>
                    </li>
                </ul>
            </div>
            @if ($canUpdate === 1)
            <div class="card-footer">
                <a href="{{ url('employee/employee-lists/' .$employee->id . '/edit') }}" class="btn btn-outline-primary btn-block btn-sm"><b><i class="fa fa-edit"></i> Edit Record</b></a>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <!-- Qualification -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Qualification (s)</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Qualification</th>
                                <th class="text-center">School/University</th>
                                <th class="text-center">Subject/Course</th>
                                <th class="text-center">Completed On</th>
                                <th class="text-center">Aggregate Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->empQualifications as $qualification)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $qualification->qualification->name }}</td>
                                <td>{{ $qualification->school }}</td>
                                <td>{{ $qualification->subject }}</td>
                                <td>{{ $qualification->completion_year }}</td>
                                <td>{{ $qualification->aggregate_score }}</td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">No qualification found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Trainings -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Training (s)</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Training Title</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">Duration</th>
                                <th class="text-center">Place/Location</th>
                                <th class="text-center">Certificate</th>
                                <th class="text-center">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->empTrainings as $training)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $training->title }}</td>
                                <td>{{ $training->start_date }}</td>
                                <td>{{ $training->end_date }}</td>
                                <td>{{ $training->duration }}</td>
                                <td>{{ $training->location }}</td>
                                <td>{{ $training->description }}</td>
                                <td>{{ $training->certificate }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">No training found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Experiences -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-1 mt-1">Experience(s)</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Qualification</th>
                                <th class="text-center">Organization</th>
                                <th class="text-center">Place/Location</th>
                                <th class="text-center">Designation</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->empExperiences as $experience)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $experience->organization }}</td>
                                <td>{{ $experience->place }}</td>
                                <td>{{ $experience->designation }}</td>
                                <td>{{ $experience->start_date }}</td>
                                <td>{{ $experience->end_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">No experience found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('page_scripts')
<script>
    $(document).ready(function() {
    $('.btn-tool').on('click', function() {
        var icon = $(this).find('i');
        icon.toggleClass('fa-plus fa-minus'); // Toggle the icon
    });
});
</script>
@endpush