@extends('layouts.app')
@section('page-title', 'Department')
@section('content')

<form action="{{ url('master/departments') }}" class="js-validation-bootstrap" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="short_name">Short Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="short_name" name="short_name" value="{{ old('short_name') }}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Department <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="mas_employee_id">Department Head</label>
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
            'cancelUrl' => url('master/departments') ,
            'cancelName' => 'CANCEL'
            ])
           
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection