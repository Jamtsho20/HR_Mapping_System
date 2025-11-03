@extends('layouts.app')
@section('page-title', 'Edit Training List')
@section('content')

<form action="{{ route('training-application.training-lists.update', $trainingList->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Training List Details -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> TRAINING LIST DETAILS</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Title -->
                <div class="col-md-4 mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $trainingList->title) }}" class="form-control" required>
                </div>

                <!-- Training Type -->
                <div class="col-md-4 mb-3">
                    <label for="type_id">Training Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="type_id" name="type_id" required>
                        <option value="" disabled hidden>Select your option</option>
                        @foreach ($trainingTypes as $type)
                            <option value="{{ $type->id }}" {{ old('type_id', $trainingList->type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Country -->
                <div class="col-md-4 mb-3" id="country_wrapper" style="display:none;">
                    <label for="country_id">Country <span class="text-danger">*</span></label>
                    <select class="form-control" id="country_id" name="country_id">
                        <option value="" disabled hidden>Select your option</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $trainingList->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dzongkhag -->
                <div class="col-md-4 mb-3" id="dzongkhag_wrapper" style="display:none;">
                    <label for="dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                    <select class="form-control" id="dzongkhag_id" name="dzongkhag_id">
                        <option value="" disabled hidden>Select your option</option>
                        @foreach ($dzongkhags as $dz)
                            <option value="{{ $dz->id }}" {{ old('dzongkhag_id', $trainingList->dzongkhag_id) == $dz->id ? 'selected' : '' }}>
                                {{ $dz->dzongkhag }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Training Nature -->
                <div class="col-md-4 mb-3">
                    <label for="training_nature_id">Training Nature <span class="text-danger">*</span></label>
                    <select class="form-control" id="training_nature_id" name="training_nature_id" required>
                        <option value="" disabled hidden>Select your option</option>
                        @foreach ($trainingNatures as $nature)
                            <option value="{{ $nature->id }}" {{ old('training_nature_id', $trainingList->training_nature_id) == $nature->id ? 'selected' : '' }}>
                                {{ $nature->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Funding Type -->
                <div class="col-md-4 mb-3">
                    <label for="funding_type_id">Funding Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="funding_type_id" name="funding_type_id" required>
                        <option value="" disabled hidden>Select your option</option>
                        @foreach ($fundingTypes as $funding)
                            <option value="{{ $funding->id }}" {{ old('funding_type_id', $trainingList->funding_type_id) == $funding->id ? 'selected' : '' }}>
                                {{ $funding->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location', $trainingList->location) }}" class="form-control" required>
                </div>

                <!-- Institute -->
                <div class="col-md-4 mb-3">
                    <label for="institute" class="form-label">Institute <span class="text-danger">*</span></label>
                    <input type="text" name="institute" id="institute" value="{{ old('institute', $trainingList->institute) }}" class="form-control" required>
                </div>

                <!-- Start Date -->
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $trainingList->start_date) }}" class="form-control" required>
                </div>

                <!-- End Date -->
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $trainingList->end_date) }}" class="form-control" required>
                </div>

                <!-- Department -->
                <div class="col-md-4 mb-3">
                    <label for="department_id">Department <span class="text-danger">*</span></label>
                    <select class="form-control" id="department_id" name="department_id" required>
                        <option value="" disabled hidden>Select your option</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $trainingList->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div class="col-md-4 mb-3">
                    <label for="amount_allocated">Amount Allocated <span class="text-danger">*</span></label>
                    <input type="text" step="0.01" name="amount_allocated" id="amount_allocated" value="{{ old('amount_allocated', $trainingList->amount_allocated) }}" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Training Budget Allocation -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-money me-2"></i> TRAINING BUDGET ALLOCATION</h5>
        </div>
        <div class="card-body">
            @include('training-application.training-lists.edit.budget', ['trainingList' => $trainingList])
        </div>
    </div>

    <!-- Training Bonds -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-handshake-o me-2"></i> TRAINING BONDS</h5>
        </div>
        <div class="card-body">
            @include('training-application.training-lists.edit.bond', ['trainingList' => $trainingList])
        </div>
    </div>

    <!-- Form Footer -->
    <div class="card-footer text-center">
        @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('training/training-lists'),
            'cancelName' => 'CANCEL'
        ])
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    function toggleFields() {
        const typeId = document.getElementById("type_id").value;
        const countryWrapper = document.getElementById("country_wrapper");
        const dzongkhagWrapper = document.getElementById("dzongkhag_wrapper");

        if (typeId == 1) { // In-country
            dzongkhagWrapper.style.display = "block";
            document.getElementById("dzongkhag_id").required = true;
            countryWrapper.style.display = "none";
            document.getElementById("country_id").required = false;
        } else if (typeId == 2) { // Ex-country
            countryWrapper.style.display = "block";
            document.getElementById("country_id").required = true;
            dzongkhagWrapper.style.display = "none";
            document.getElementById("dzongkhag_id").required = false;
        } else {
            countryWrapper.style.display = "none";
            dzongkhagWrapper.style.display = "none";
        }
    }

    document.getElementById("type_id").addEventListener("change", toggleFields);
    window.addEventListener("DOMContentLoaded", toggleFields);
</script>
@endpush
