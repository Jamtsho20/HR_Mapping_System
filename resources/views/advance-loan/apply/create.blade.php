@extends('layouts.app')
@section('page-title', 'Create Advance')
@section('content')

<form action="{{ route('apply.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="advance_no">Advance No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="advance_no" value="{{ old('advance_no') }}" id="advance_no" value="{{ old('advance_no') }}" placeholder="Generating..." readonly>
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
                        <label for="date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" id="date" readonly required>
                    </div>
                </div>

            </div>
            <!-- Advance-to-staff Form -->
            @include('advance-loan.apply.types.advance_to_staff')

            <!--DSA Advance Form-->
            @include('advance-loan.apply.types.dsa_advance')

            <!-- Salary_advance Form-->
            @include('advance-loan.apply.types.salary_advance')

            <!-- General_imprest_advance Form -->
            @include('advance-loan.apply.types.general_imprest_advance')

            <!-- Electricity_imprest_advanceForm-->
            @include('advance-loan.apply.types.electricity_imprest_advance')

            <!-- Sifa_loan Form -->
            @include('advance-loan.apply.types.sifa_loan')

            <!-- Gadget Emi Form -->
            @include('advance-loan.apply.types.gadget_emi')
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="purpose">Remark</label>
                        <textarea rows="2" class="form-control" name="remark" id="remark">{{ old('remark') }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="attachment">Attachment <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment" required accept="image/*"/>
                    </div>
                </div>
            </div>



        </div>


        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Create Advance</button>
            <a href="{{ url('advance-loan/apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var advanceTypeSelect = document.getElementById('advance_type');
        var formSections = document.querySelectorAll('.dynamic-form');

        advanceTypeSelect.addEventListener('change', function() {
            var selectedType = advanceTypeSelect.value;

            // Hide all dynamic form sections and disable their inputs
            formSections.forEach(function(section) {
                section.style.display = 'none';
                disableFormFields(section);
            });

            // Show and enable the corresponding form section based on the selected type
            if (selectedType === '1') {
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
            } else if (selectedType === '4') {
                var section = document.getElementById('gadget-emi-form');
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
                var section = document.getElementById('sifa-loan-form');
                section.style.display = 'block';
                enableFormFields(section);
            }
        });

        // Initially hide all dynamic form sections
        formSections.forEach(function(section) {
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
            form.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.disabled = false; // Enable the input fields
            });
        }

        // Function to disable form fields in hidden sections
        function disableFormFields(form) {
            form.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.disabled = true; // Disable the input fields
            });
        }
    });
</script>
@endpush