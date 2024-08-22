@extends('layouts.app')
@section('page-title', 'Emplyee Create')
@section('content')

<form action="{{ url('employee-group/employee-create') }}" class="js-validation-bootstrap" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}" required="required">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employees">Select Employees <span class="text-danger">*</span></label>
                        <select name="employees[]" id="employees" class="form-control" multiple="multiple">
                            <option value="" disabled selected hidden>Select an option</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->emp_id_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('employee-group/employee-create') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection