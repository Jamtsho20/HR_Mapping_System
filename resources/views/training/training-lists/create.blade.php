@extends('layouts.app')
@section('page-title', 'Create New Training Lists')
@section('content')

<form action="{{ route('training-lists.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" required>
                </div>

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

                <div class="col-md-4 mb-3" id="country_wrapper" style="display:none;">
                    <label for="country_id">Country <span class="text-danger">*</span></label>
                    <select class="form-control" id="country_id" name="country_id">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($country as $c)
                        <option value="{{ $c->id }}" {{ old('country_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3" id="dzongkhag_wrapper" style="display:none;">
                    <label for="dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                    <select class="form-control" id="dzongkhag_id" name="dzongkhag_id">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($dzonkhag as $dz)
                        <option value="{{ $dz->id }}" {{ old('dzongkhag_id') == $dz->id ? 'selected' : '' }}>
                            {{ $dz->dzongkhag }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
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

                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="institute" class="form-label">Institute <span class="text-danger">*</span></label>
                    <input type="text" name="institute" id="institute" value="{{ old('institute') }}" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control" required>
                </div>
            </div>

            <div class="row">
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
                <div class="col-md-4 mb-3">
                    <label for="amount_allotted">Amount Allocated <span class="text-danger">*</span></label>
                    <input type="text" step="0.01" name="amount_allotted" id="amount_allotted" value="{{ old('amount_allotted') }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('training/training-lists'),
            'cancelName' => 'CANCEL'
            ])
        </div>
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