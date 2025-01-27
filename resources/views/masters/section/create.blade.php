@extends('layouts.app')
@section('page-title', 'Section')
@section('content')
<form action="{{ url('master/section') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="example-select">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="example-select" name="mas_department_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_employee_id">Section Head</label>
                        <select class="form-control" name="mas_employee_id">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach (employeeList() as $employee)
                            <option value="{{ $employee->id }}" {{ old('mas_employee_id') == $employee->id ? 'selected' : '' }}>
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
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/section') ,
            'cancelName' => 'CANCEL'
            ])
           
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection