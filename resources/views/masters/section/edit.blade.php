@extends('layouts.app')
@section('page-title', 'Section')
@section('content')

<form action="{{ url('master/section/' . $section->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_department_id">Department <span class="text-danger">*</span></label>
                        <select name="mas_department_id" class="form-control" required>
                            <option value="" disabled>Select your option</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ $section->mas_department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $section->name }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_employee_id">Section Head</label>
                        <select class="form-control" name="mas_employee_id">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach (employeeList() as $employee)
                            <option value="{{ $employee->id }}" {{ $section->mas_employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/section') ,
            'cancelName' => 'CANCEL'
            ])
           
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection