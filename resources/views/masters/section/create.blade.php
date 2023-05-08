@extends('layouts.app')
@section('page-title', 'Section')
@section('content')
<form action="{{ url('master/section') }}" method="POST">
    @csrf
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">

            <div class="form-group">
                <label for="example-select">Department <span class="text-danger">*</span></label>
                <select class="form-control" id="example-select" name="mas_department_id" required="required">
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name  }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
        <a href="{{ url('master/section') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection