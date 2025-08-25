@extends('layouts.app')
@section('page-title', 'Create Advance')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@include('layouts.includes.loader')
<form action="{{ route('apply.store') }}" id="apply_advance" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="transaction_no">Advance No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="transaction_no" value="{{ old('transaction_no') }}" id="transaction_no" value="{{ old('transaction_no') }}" placeholder="Generating..." readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="advance_type">Advance/Loan Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="advance_type" name="advance_type">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach($advanceTypes as $type)
                            <option value="{{ $type->id }}" {{ old('advance_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger"></span></label>

                        <!-- Display formatted date for the user (e.g., 10-Jan-2025) -->
                        <input type="text" class="form-control" id="formatted-dates" value="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" readonly>

                        <!-- Hidden input field to store date in YYYY-MM-DD format (for database) -->
                        <input type="hidden" name="date" id="hidden-date" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

            </div>
            <!-- Advance-to-staff Form -->
            @include('advance-loan.apply.types.advance_to_staff')

            <!-- Salary_advance Form-->
            @include('advance-loan.apply.types.salary_advance')

            <!-- General_imprest_advance Form -->
            @include('advance-loan.apply.types.general_imprest_advance')

            <!-- Electricity_imprest_advanceForm-->
            {{-- @include('advance-loan.apply.types.electricity_imprest_advance') --}}

            <!-- Sifa_loan Form -->
            @include('advance-loan.apply.types.sifa_loan')

            <!-- Gadget Emi Form -->
            @include('advance-loan.apply.types.gadget_emi')

            <!--DSA Advance Form-->
            @include('advance-loan.apply.types.dsa_advance')

        </div>


        <div class="card-footer">
            <button type="submit" id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i> SUBMIT</button>
            <a href="{{ url('advance-loan/apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('apply_advance');
    const loader = document.getElementById('loader');
    const submitBtn = document.getElementById('submitBtn');
 const isSifaRegistered = {{ (int) $isSifaRegistered }};

    // Pass the user's employment type ID dynamically
    var employmentTypeId = {{ auth()->user()->empJob && auth()->user()->empJob->empType ? auth()->user()->empJob->empType->id : 'null' }};

    form.addEventListener('submit', function (e) {
        loader.style.display = 'flex'; // Show loader
    });

    var advanceTypeSelect = document.getElementById('advance_type');
    var formSections = document.querySelectorAll('.dynamic-form');

    advanceTypeSelect.addEventListener('change', function () {
        var selectedType = advanceTypeSelect.value;

        // Hide all dynamic form sections and disable their inputs
        formSections.forEach(function (section) {
            section.style.display = 'none';
            disableFormFields(section);
        });

        // Show and enable the corresponding form section based on the selected type
        if (selectedType === '4') {
            if ([3, 6, 7, 8].includes(employmentTypeId)) {
                var msg = 'You are not allowed to apply based on your employment type';
                showErrorMessage(msg);
            } else {
                var section = document.getElementById('gadget-emi-form');
                section.style.display = 'block';
                enableFormFields(section);
            }
        } else if (selectedType === '1') {
            var section = document.getElementById('advance-to-staff-form');
            section.style.display = 'block';
            enableFormFields(section);
        } else if (selectedType === '2') {
            var section = document.getElementById('dsa-advance-form');
            section.style.display = 'block';
            enableFormFields(section);
        } else if (selectedType === '3') {
            var section = document.getElementById('electricity-imprest-advance-form');
            section.style.display = 'block';
            enableFormFields(section);
        } else if (selectedType === '5') {
            var section = document.getElementById('general-imprest-advance-form');
            section.style.display = 'block';
            enableFormFields(section);
        } else if (selectedType === '6') {
            var section = document.getElementById('salary-advance-form');
            section.style.display = 'block';
            enableFormFields(section);
        } else if (selectedType === '7') {
    if (!isSifaRegistered) {
        showErrorMessage('You are not a registered SIFA member.');
    } else {
        const today = new Date();
        const currentDay = today.getDate();

        if (currentDay > 21) {
            showErrorMessage('SIFA Loan applications are only allowed from the 1st to the 21st of each month.');
            advanceTypeSelect.value = ''; // Reset selection
            formSections.forEach(function (section) {
                section.style.display = 'none';
                disableFormFields(section);
            });
        } else {
            var section = document.getElementById('sifa-loan-form');
            section.style.display = 'block';
            enableFormFields(section);
        }
    }
}
    });

    // Initially hide all dynamic form sections
    formSections.forEach(function (section) {
        section.style.display = 'none';
        disableFormFields(section);
    });

    // Show the correct form section based on the old input value
    var oldAdvanceType = '{{ old("advance_type") }}';
    if (oldAdvanceType) {
        advanceTypeSelect.value = oldAdvanceType;
        advanceTypeSelect.dispatchEvent(new Event('change')); // Trigger the change event to show the relevant section
    }

    // Function to enable form fields in the visible section
    function enableFormFields(form) {
        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            input.disabled = false; // Enable the input fields
        });
    }

    // Function to disable form fields in hidden sections
    function disableFormFields(form) {
        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            input.disabled = true; // Disable the input fields
        });
    }
});

</script>
@endpush
