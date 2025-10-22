@extends('layouts.app')
@section('page-title', 'Add New Training')
@section('content')

<form action="{{ route('my-profile.my-training.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card ">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> TRAINING LIST DETAILS</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Title -->
                <div class="col-md-4 mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" required>
                </div>

                <!-- Training Type -->
                <div class="col-md-4 mb-3">
                    <label for="type_id">Training Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="type_id" name="type_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingTypes as $type)
                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                

                <!-- Training Nature -->
                <div class="col-md-4 mb-3">
                    <label for="training_nature_id">Training Nature <span class="text-danger">*</span></label>
                    <select class="form-control" id="training_nature_id" name="training_nature_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingNatures as $type)
                        <option value="{{ $type->id }}" {{ old('training_nature_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Funding Type -->
                <div class="col-md-4 mb-3">
                    <label for="funding_type_id">Funding Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="funding_type_id" name="funding_type_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($fundingTypes as $type)
                        <option value="{{ $type->id }}" {{ old('funding_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control" required>
                </div>

                <!-- Institute -->
                <div class="col-md-4 mb-3">
                    <label for="institute" class="form-label">Institute <span class="text-danger">*</span></label>
                    <input type="text" name="institute" id="institute" value="{{ old('institute') }}" class="form-control" required>
                </div>

                <!-- Start Date -->
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control" required>
                </div>

                <!-- End Date -->
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control" required>
                </div>

                <!-- Department -->
                <div class="col-md-4 mb-3">
                    <label for="department_id">Department <span class="text-danger">*</span></label>
                    <select class="form-control" id="department_id" name="department_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($department as $type)
                        <option value="{{ $type->id }}" {{ old('department_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div class="col-md-4 mb-3">
                    <label for="amount_allocated">Amount Allocated <span class="text-danger">*</span></label>
                    <input type="text" step="0.01" name="amount_allocated" id="amount_allocated" value="{{ old('amount_allocated') }}" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-center">
        @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('my-profile.my-training'),
            'cancelName' => 'CANCEL'
        ])
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')

@endpush
