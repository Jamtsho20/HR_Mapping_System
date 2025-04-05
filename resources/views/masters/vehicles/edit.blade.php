@extends('layouts.app')
@section('page-title', 'Edit Vehicle')
@section('content')
<form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Vehicle Number -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vehicle_no">Vehicle No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="vehicle_no" value="{{ old('vehicle_no', $vehicle->vehicle_no) }}" placeholder="Ex. BP1F1111" required>
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                        <select class="form-control select2 select2-hidden-accessible" id="vehicle_type" name="vehicle_type" required>
                            <option value="">Select Vehicle Type</option>
                            @foreach($vehicleTypes as $id => $name)
                                <option value="{{ $id }}" {{ old('vehicle_type', $vehicle->vehicle_type_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Location -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="location" value="{{ old('location', $vehicle->location) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Final Reading -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="final_reading">Final Reading <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="final_reading" value="{{ old('final_reading', $vehicle->final_reading) }}" required>
                    </div>
                </div>

                <!-- Department -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="department_id">Department <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ old('department_id', $vehicle->department_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <label class="custom-switch">
                        <input type="hidden" name="status[is_active]" value="0">
                        <input type="checkbox" name="status[is_active]" class="custom-switch-input" value="1" {{ old('status.is_active', $vehicle->is_active) == 1 ? 'checked' : '' }}>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Is Active</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'UPDATE',
                'cancelUrl' => url('master/vehicles'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush
