@extends('layouts.app')
@section('page-title', 'Create New Vehicles')
@section('content')

<form action="{{ route('vehicles.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vehicle_no">Vehicle No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="vehicle_no" value="{{ old('vehicle_no') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="example-select">Vehicle Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="example-select" name="vehicle_type" required="required">
                        <option value="" disabled selected hidden>Select your option</option>
                        <option value="1">Light</option>
                        <option value="2">Medium</option>
                        <option value="3">Heavy</option>
                        <option value="4">Two Wheeler</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked, and retain old value -->
                        <input type="checkbox"
                            name="status[is_active]"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>

            <div class="card-footer text-center">
                @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => url('master/vehicles') ,
                'cancelName' => 'CANCEL'
                ])

            </div>
        </div>
    </div>
</form>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush