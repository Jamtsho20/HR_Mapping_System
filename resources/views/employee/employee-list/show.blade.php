@extends('layouts.app')
@section('page-title', 'Employee Details')
@section('buttons')
    <a href="{{ url('employee/employee-lists/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Employee
        List</a>
@endsection
@section('content')
    @if ($canUpdate === 1)
        <div class="d-flex flex-row-reverse">
            <a href="{{ url('employee/employee-lists/' . $employee->id . '/edit') }}"
                class="col-sm-2 btn btn-outline-primary btn-block btn-sm "><b><i class="fa fa-edit"></i> Edit Record</b>
            </a>
        </div>
        <br>
    @endif
    <div class="row">
        <!-- Personal Details -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $employee->name }} ({{ $employee->username }})
                        <span
                            class="badge rounded-pill  bg-{{ $employee->is_active == 1 ? 'danger' : 'success' }} me-1 mt-1 ">{{ $employee->is_active }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2  d-flex justify-content-center align-items-center ">
                            <img src="{{ $employee->profile_picture }}" class="rounded-circle" style="width: 130px;"
                                alt="Profile" />
                        </div>
                        <div class="col-md-4">
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
                                    <a class="pull-right">{{ $employee->gender_name }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-unbordered">

                                <li class="list-group-item">
                                    <b>Marital Status</b>
                                    <a class="pull-right">{{ $employee->marital_status_name }}</a>
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
                    </div>

                </div>

            </div>
        </div>
        <!-- Address -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-1 mt-1">Address Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Present Address -->
                        <div class="col-md-6">
                            <h6>Present Address</h6>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Dzongkhag</b> <a
                                        class="pull-right">{{ $employee->empPresentAddress->masDzongkhag->dzongkhag }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Gewog</b> <a
                                        class="pull-right">{{ $employee->empPresentAddress->masGewog->name ?? config('global.null_value') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>City</b> <a class="pull-right">{{ $employee->empPresentAddress->city }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Postal Code</b> <a
                                        class="pull-right">{{ $employee->empPresentAddress->postal_code }}</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Permanent Address -->
                        <div class="col-md-6">
                            <h6>Permanent Address</h6>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Dzongkhag</b> <a
                                        class="pull-right">{{ $employee->empPermenantAddress->masDzongkhag->dzongkhag }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Gewog</b> <a
                                        class="pull-right">{{ $employee->empPermenantAddress->masGewog->name ?? config('global.null_value') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>VIllage</b> <a
                                        class="pull-right">{{ $employee->empPermenantAddress->masVillage->village ?? config('global.null_value') }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Tharm No</b> <a
                                        class="pull-right">{{ $employee->empPermenantAddress->thram_no ?? config('global.null_value') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>House No</b> <a
                                        class="pull-right">{{ $employee->empPermenantAddress->house_no ?? config('global.null_value') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Employee Job related -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Job Details</h3>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Department</b> <a
                                        class="pull-right">{{ $employee->empJob->department->code_name }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Section</b> <a
                                        class="pull-right">{{ $employee->empJob->section->name ?? config('global.null_value') }}</a>
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
                                    <b>Employee Group (s)</b> <a
                                        class="pull-right">{{ convert_array_to_string($employeeGroupNames, ', ') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Supervisor</b>
                                    <a
                                        class="pull-right">{{ $employee->empJob->supervisor->emp_id_name ?? config('global.null_value') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <li class="list-group-item">
                                <b>Job Location</b>
                                <a class="pull-right">{{ $employee->empJob->office->name }}
                                    ({{ $employee->empJob->office->dzongkhag->dzongkhag }})</a>
                            </li>
                            <li class="list-group-item">
                                <b>Address</b> <a class="pull-right">{{ $employee->empJob->office->address }} </a>
                            </li>
                            <li class="list-group-item">
                                <b>Pay Scale</b> <a class="pull-right">{{ $employee->empJob->gradeStep->pay_scale }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Basic Pay</b> <a class="pull-right">{{ $employee->empJob->basic_pay }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Salary Disbursement Mode</b> <a
                                    class="pull-right">{{ $employee->empJob->salary_disbursement_name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Bank</b> <a class="pull-right">{{ $employee->empJob->bank }}
                                    ({{ $employee->empJob->account_number ?? config('global.null_value')}}) </a>
                            </li>
                            <li class="list-group-item">
                                <b>PF Number</b> <a class="pull-right">{{ $employee->empJob->pf_number }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>TPN Number</b> <a class="pull-right">{{ $employee->empJob->tpn_number }}</a>
                            </li>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--qualification-->
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
                                        <td>{{ $qualification->qualification->name ?? '-' }}</td>
                                        <td>{{ $qualification->school ?? '-' }}</td>
                                        <td>{{ $qualification->subject ?? '-' }}</td>
                                        <td>{{ $qualification->completion_year ?? '-' }}</td>
                                        <td>{{ $qualification->aggregate_score ?? '-' }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger">No qualification found</td>
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
                                        <td>
                                            @if ($training->certificate)
                                                <a href="{{ asset($training->certificate) }}" target="_blank"
                                                    class="btn btn-link">
                                                    <i class="fas fa-file-alt"></i> View
                                                </a>
                                            @endif
                                        </td>
                                        <td class="{{ !$training->description ? 'text-center' : '' }}">
                                            {{ $training->description ?? config('global.null_value') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger">No training found</td>
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
                                    <th class="text-center">Country</th>
                                    <th class="text-center">Designation</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">End Date</th>
                                    <th class="text-center">Description</th>
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
                                        <td>{{ $experience->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger">No experience found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Documents -->
        <div class=" col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-1 mt-1">Document (s)</h3>
                </div>
                <div class="card-body">
                    <div class="row ">

                        <div class="col-md-12">
                            @if (isset($employee->empDoc->employment_contract))
                                <a href="{{ asset($employee->empDoc->employment_contract) }}" class="btn btn-primary"
                                    target="_blank"><i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                                    &nbsp; Employement Contract</a>
                            @endif


                            @if (isset($employee->empDoc->non_disclosure_aggrement))
                                <a href="{{ asset($employee->empDoc->non_disclosure_aggrement) }}"
                                    class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf-o text-secondary"
                                        aria-hidden="true"></i>
                                    &nbsp; Non Disclosure Aggrement</a>
                            @endif
                            @if (isset($employee->empDoc->job_responsibilities))
                                <a href="{{ asset($employee->empDoc->job_responsibilities) }}" class="btn btn-primary"
                                    target="_blank"><i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                                    &nbsp; Job Responsibilities</a>
                            @endif


                            @if (isset($employee->empDoc->other))
                                @foreach (json_decode($employee->empDoc->other) as $other)
                                    <a href="{{ asset($other) }}" class="btn btn-primary" target="_blank"><i
                                            class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                                        &nbsp; Others</a>
                                @endforeach
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Appointment Order -->
        <div class=" col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-1 mt-1">Appointment Order (s)</h3>
                </div>
                <div class="card-body">
                    <div class="row ">

                        <div class="col-md-12">
                            @if ($employee->appointment_order)
                                <a href="{{ Storage::url($employee->appointment_order) }}" class="btn btn-primary"
                                    target="_blank">
                                    <i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>&nbsp; Probation
                                    Appointment Order
                                </a>
                            @endif

                            @if ($employee->regular_appointment_order)
                                <a href="{{ Storage::url($employee->regular_appointment_order) }}"
                                    class="btn btn-primary" target="_blank" target="_blank" target="_blank">
                                    <i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>&nbsp; Regular
                                    Appointment Order
                                </a>
                            @endif




                        </div>

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
