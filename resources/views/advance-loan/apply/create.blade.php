@extends('layouts.app')
@section('page-title', 'Create Expense')
@section('content')

<form action="{{ route('apply.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="advane_no">Advance No <span class="text-danger">*</span></label>
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
                            <option value="dsa_advance">DSA Advance</option>
                            <option value="salary_advance">Salary Advance</option>
                            <option value="general_imprest_advance">General Imprest Advance</option>
                            <option value="electricity_imprest_advance">Electricity Imprest Advance</option>
                            <option value="advance_to_staff">Advance to Staff</option>
                            <option value="sifa_loan">SIFA Loan</option>
                            <option value="gadget_emi">Gadget EMI</option>
                        </select>  
                    </div>
                </div>

            </div>
        </div>
        <!--DSA Advance Form-->
        @include('advance-loan.apply.types.dsa_advance')
       
        <!-- Salary_advance Form-->
        @include('advance-loan.apply.types.salary_advance')

        <!-- General_imprest_advance Form -->
        @include('advance-loan.apply.types.general_imprest_advance')

       <!-- Electricity_imprest_advanceForm-->
       @include('advance-loan.apply.types.electricity_imprest_advance')

       <!-- Advance-to-staff Form -->
        @include('advance-loan.apply.types.advance_to_staff')

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
    $(document).ready(function() {
        // Hide all dynamic forms initially
        $(".dynamic-form").hide();

        // Show the selected form based on the advance_loan_type
        $('#advance-loan-type').on('change', function() {
            var selection = $(this).val().toLowerCase().replace(/_/g, '-');
            $(".dynamic-form").hide(); // Hide all forms
            switch (selection) {
                case "dsa-advance":
                    $("#dsa-advance-form").show();
                    break;
                case "salary-advance":
                    $("#salary-advance-form").show();
                    break;
                case "general-imprest-advance":
                    $("#general-imprest-advance-form").show();
                    break;
                case "electricity-imprest-advance":
                    $("#electricity-imprest-advance-form").show();
                    break;
                case "advance-to-staff":
                    $("#advance-to-staff-form").show();
                    break;
                case "sifa-loan":
                    $("#sifa-loan-form").show();
                    break;
                case "gadget-emi":
                    $("#gadget-emi-form").show();
                    break;
                default:
                    $(".dynamic-form").hide(); // Hide all if no match
            }
        });
    });
</script>
@endpush


