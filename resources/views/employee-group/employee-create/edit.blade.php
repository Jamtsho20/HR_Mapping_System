@extends('layouts.app')
@section('page-title', 'Edit Employee Group')
@section('content')
<form action="{{ url('employee-group/employee-create/' . $employeeGroup->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $employeeGroup->name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="" disabled selected hidden>Select status</option>
                    <option value="1" {{ old('status', $employeeGroup->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $employeeGroup->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="description">{{ old('description', $employeeGroup->description) }}</textarea>
            </div>
            
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('employee-group/employee-create') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
