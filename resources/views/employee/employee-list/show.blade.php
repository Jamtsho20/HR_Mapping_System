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
                <h3 class="card-title mb-1 mt-1">Qualification(s)

                </h3>

            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    @foreach($employee->empQualifications as $qualification)
                        <li class="list-group-item">
                            <b>Qualification</b> <a class="pull-right">{{ $qualification->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>School</b> <a class="pull-right">{{ $qualification->school }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Subject</b> <a class="pull-right">{{ $qualification->subject }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Completion Year</b> <a class="pull-right">{{ $qualification->completion_year }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Aggregate Score</b> <a class="pull-right">{{ $qualification->aggregate_score }}</a>
                        </li>
                    @endforeach
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
                    @foreach($employee->empExperiences as $experience)
                        <li class="list-group-item">
                            <b>Organization</b> <a class="pull-right">{{ $experience->organization }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Place</b> <a class="pull-right">{{ $experience->place }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Designation</b> <a class="pull-right">{{ $experience->designation }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Start Date</b> <a class="pull-right">{{ $experience->start_date }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>End Date</b> <a class="pull-right">{{ $experience->end_date }}</a>
                        </li>
                    @endforeach
                </ul>
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