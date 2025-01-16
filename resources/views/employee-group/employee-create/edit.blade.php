@extends('layouts.app')
@section('page-title', 'Edit Employee Group')
@section('content')
<form action="{{ url('employee-group/employee-create/' . $employeeGroup->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $employeeGroup->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="" disabled selected hidden>Select status</option>
                            <option value="1" {{ old('status', $employeeGroup->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $employeeGroup->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description">{{ old('description', $employeeGroup->description) }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employees">Select Employees <span class="text-danger">*</span></label>
                        <select name="employees[]" id="employees" class="js-select2 form-control" style="width: 100%;" data-placeholder="Choose many..." multiple>
                            <option value="" disabled>Select an option</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ in_array($employee->id, $selectedEmployees) ? 'selected' : '' }}>
                                    {{ $employee->emp_id_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
    <script>
        $(function() {
            $('.js-select2').select2();
        });
    </script>
@endpush
