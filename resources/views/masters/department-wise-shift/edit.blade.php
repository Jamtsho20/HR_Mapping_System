@extends('layouts.app')
@section('page-title', 'Edit Department Wise Shift')
@section('content')

<form action="{{ route('department-wise-shift.update', $shift->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $shift->name) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="department_id">Department <span class="text-danger">*</span></label>
                        <select class="form-control" name="department_id" required>
                            <option value="" disabled>Select your option</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $shift->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type_id">Shift Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="type_id" required>
                            <option value="" disabled>Select your option</option>
                            @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id', $shift->type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $shift->start_time) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $shift->end_time) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group "></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active', $shift->status) == 1 ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('master/department-wise-shift'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush