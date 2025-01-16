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
                <label for="code">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" value="{{$department->code}}" required="required">
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
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/departments') ,
            'cancelName' => 'CANCEL'
            ])
           
        </div>
    </div>

</form>
@include('layouts.includes.delete-modal')
@endsection