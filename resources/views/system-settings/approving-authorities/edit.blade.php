@extends('layouts.app')
@section('page-title', 'Edit Approving Authority')
@section('content')

<form action="{{ url('system-setting/approving-authorities/' . $approvingAuthority->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $approvingAuthority->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="role_id">Role ID <span class="text-danger">*</span></label>
                        <select class="form-control" name="role_id">
                            <option value="">-- Select Role --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $approvingAuthority->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="description" value="{{ old('description', $approvingAuthority->description) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when the switch is unchecked -->
                        <input type="hidden" name="has_employee_field" value="0">
                        <!-- Checkbox to pass '1' when checked -->
                        <input type="checkbox"
                            name="has_employee_field"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('has_employee_field', $approvingAuthority->has_employee_field) == 1 ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Has Employee Field</span>
                    </label>
                </div>


                <div class="col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active', $approvingAuthority->status) == 1 ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>

            </div>
            <br><br>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Update</button>
                <a href="{{ url('system-setting/approving-authorities') }}" class="btn btn-danger"><i class="fa fa-undo"></i> Cancel</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush