@extends('layouts.app')
@section('page-title', 'My Profile')
@section('content')
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
                <div class="col-md-2 d-flex justify-content-center align-items-center position-relative">
                    <div style="position: relative; display: inline-block;">
                        <img src="{{$employee->profile_picture}}" class="rounded-circle" style="width: 150px; height: 150px;" alt="Profile" />

                        <form action="{{ route('user-profile.updateImage', $employee->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="file" id="profileImageInput" name="profile_pic" class="d-none" onchange="this.form.submit()">

                            <button type="button" onclick="document.getElementById('profileImageInput').click()"
                                style="position: absolute; bottom: 5px; right: 5px; background-color: #2e86c1 ; border: none; padding: 10px; cursor: pointer; font-size: 20px; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="list-group list-group-unbordered">

                        <li class="list-group-item">
                            <b>Contact No</b> <a class="pull-right">{{ $employee->contact_number }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email Id</b> <a class="pull-right">{{ $employee->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Date of Birth</b> <a class="pull-right">{{ $employee->dob }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Gender</b>
                            <a class="pull-right">{{ $employee->gender_name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Grade</b>
                            <a class="pull-right">{{ optional(optional($employee->empJob)->grade)->name ?? 'N/A' }}</a>
                        </li>

                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group list-group-unbordered">

                        <li class="list-group-item">
                            <b>Employment Type</b>
                            <a class="pull-right">{{ optional(optional($employee->empJob)->empType)->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>DOJ</b> <a class="pull-right">{{ $employee->date_of_appointment }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Manager</b> <a class="pull-right">{{ optional(optional($employee->empJob)->supervisor)->emp_id_name ?? config('global.null_value') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Section</b> <a class="pull-right">{{ optional(optional($employee->empJob)->section)->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Department</b> <a class="pull-right">{{ optional(optional($employee->empJob)->department)->code_name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Roles</b>
                            <ul class="mb-0 text-end">
                                @forelse($employee->roles as $role)
                                <li>{{ $role->name }}</li>
                                @empty
                                <li>No roles assigned</li>
                                @endforelse
                            </ul>
                        </li>


                    </ul>
                </div>
            </div>

        </div>

    </div>
   
</div>
@endsection
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">