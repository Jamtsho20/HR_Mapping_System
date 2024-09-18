@extends('layouts.app')
@section('page-title', 'Create Advance')
@section('content')

<form action="{{ route('apply.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="advance_no">Advance No <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="Generating...." name="advance_no" required="required" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="advance-loan-type">Advance/Loan Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="advance-loan-type" name="advance-loan-type">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach($advanceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->advancetype }}</option>
                            @endforeach
                        </select>
                    </div>
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
    var advanceTypeSelect = document.getElementById('advance-loan-type');
    var formSections = document.querySelectorAll('.dynamic-form');

    advanceTypeSelect.addEventListener('change', function() {
        var selectedType = advanceTypeSelect.value;

        // Hide all dynamic form sections
        formSections.forEach(function(section) {
            section.style.display = 'none';
        });

        // Show the corresponding form section based on the selected type
        if (selectedType === '1') { // Advance-to-staff
            document.getElementById('advance-to-staff-form').style.display = 'block';
        } else if (selectedType === '2') { // DSA Advance
            document.getElementById('dsa-advance-form').style.display = 'block';
        } else if (selectedType === '3') { // Electricity Imprest Advance
            document.getElementById('electricity-imprest-advance-form').style.display = 'block';
        } else if (selectedType === '4') { // Gadget EMI
            document.getElementById('gadget-emi-form').style.display = 'block';
        } else if (selectedType === '5') { // General Imprest Advance
            document.getElementById('general-imprest-advance-form').style.display = 'block';
        } else if (selectedType === '6') { // Salary Advance
            document.getElementById('salary-advance-form').style.display = 'block';
        } else if (selectedType === '7') { // Sifa Loan
            document.getElementById('sifa-loan-form').style.display = 'block';
        }
    });
    formSections.forEach(function(section) {
        section.style.display = 'none';
    });
});
</script>
@endpush

  