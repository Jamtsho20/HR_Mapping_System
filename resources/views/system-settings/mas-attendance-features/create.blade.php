@extends('layouts.app')
@section('page-title', 'Create New Attendance Features')
@section('content')

<form action="{{ route('mas-attendance-features.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Name -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <!-- Is Mandatory -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label d-block mt-4">&nbsp;</label>
                        <label class="custom-switch">
                            <input type="hidden" name="is_mandatory[is_active]" value="0">
                            <input type="checkbox"
                                name="is_mandatory[is_active]"
                                class="custom-switch-input"
                                value="1"
                                {{ old('is_mandatory.is_active') == '1' ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Is Mandatory</span>
                        </label>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label d-block mt-4">&nbsp;</label>
                        <label class="custom-switch">
                            <input type="hidden" name="status[is_active]" value="0">
                            <input type="checkbox"
                                name="status[is_active]"
                                class="custom-switch-input"
                                value="1"
                                {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Is Active</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
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
