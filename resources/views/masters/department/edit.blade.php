@extends('layouts.app')
@section('page-title', 'Department')
@section('content')

<form action="{{ url('master/departments/' . $department->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="short_name">Short Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="short_name" value="{{$department->short_name}}" required="required">
            </div>
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{$department->name}}" required="required">
            </div>
            <div class="form-group">
                <label for="mas_employee_id">Department Head</span></label>
                <select name="mas_employee_id" class="form-control" id="dzongkhag1">
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach (employeeList() as $employee)
                        <option value="{{ $employee->id }}" {{ $department->mas_employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
        <a href="{{ url('master/departments') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection