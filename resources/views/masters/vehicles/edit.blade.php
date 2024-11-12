@extends('layouts.app')
@section('page-title', 'Edit Vehicle')
@section('content')
<form action="{{ url('master/vehicles/' . $vehicle->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $vehicle->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_no">Vehicle No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="vehicle_no" value="{{ old('vehicle_no', $vehicle->vehicle_no) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="example-select">Vehicle Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="example-select" name="vehicle_type" required>
                            <option value="" disabled>Select your option</option>
                            <option value="1" {{ old('vehicle_type', $vehicle->vehicle_type) == 1 ? 'selected' : '' }}>Light</option>
                            <option value="2" {{ old('vehicle_type', $vehicle->vehicle_type) == 2 ? 'selected' : '' }}>Medium</option>
                            <option value="3" {{ old('vehicle_type', $vehicle->vehicle_type) == 3 ? 'selected' : '' }}>Heavy</option>
                            <option value="4" {{ old('vehicle_type', $vehicle->vehicle_type) == 4 ? 'selected' : '' }}>Two Wheeler</option>
                        </select>
                    </div>
                    <div class="col-md-64">
                        <div class="form-group"></div>
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status[is_active]" value="0">
                            <!-- Checkbox to pass '1' when checked -->
                            <input type="checkbox"
                                name="status[is_active]"
                                class="custom-switch-input form-control form-control-sm"
                                value="1"
                                {{ old('status.is_active', $vehicle->is_active) == 1 ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">is Active</span>
                        </label>
                    </div>
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
@endsection
