@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
<a href="{{ url('expense/apply-expense/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Employee
    List</a>
@endsection
@section('content')
@if ($canUpdate === 1)
<div class="d-flex flex-row-reverse">
    <a href="{{ url('expense/apply-expense/' . $expense->id . '/edit') }}"
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
                <h3 class="card-title">{{$employee->name }} ({{ $employee->username }})
                    <span
                        class="badge rounded-pill  bg-{{$employee->is_active == 1 ? 'primary' : 'danger' }} me-1 mt-1 ">{{$employee->is_active}}
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2  d-flex justify-content-center align-items-center ">
                        <img src="{{$employee->profile_picture}}" class="rounded-circle" style="width: 130px;"
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
                                <b>Employee Group (s)</b> <a class="pull-right">{{ convert_array_to_string($employeeGroupNames, ', ') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Supervisor</b>
                                <a class="pull-right">{{ $employee->empJob->supervisor->emp_id_name ?? config('global.null_value') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <li class="list-group-item">
                            <b>Job Location</b>
                            <a class="pull-right">{{ $employee->empJob->office->name }} ({{$employee->empJob->office->dzongkhag->dzongkhag}})</a>
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
                            <b>Salary Disbursement Mode</b> <a class="pull-right">{{ $employee->empJob->salary_disbursement_name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Bank</b> <a class="pull-right">{{ $employee->empJob->bank }} ({{ $employee->empJob->account_number }})</a>
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

    @endsection
    @push('page_scripts')

    @endpush