@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<div class="container mt-5">
    <form action="{{ route('sifa-registration.store') }}" method="POST" class="button-control" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <!-- Employee Selection Dropdown -->
                <div class="form-group">
                    <label for="employee">Select an employee for Sifa Registration</label>
                    <select name="employee_id" id="employee" class="form-control" required>
                        <option value="">Select an employee</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->emp_id_name }} 
                        </option>
                        @endforeach
                    </select>
                </div>

                @include('sifa.sifa-registration.forms.personalinfo')
                @include('sifa.sifa-registration.forms.sifanomination')
                @include('sifa.sifa-registration.forms.sifadependent')
                <style>
                    .file-upload-border {
                        border: 1px solid #ccc;
                        /* Light grey border */
                        border-radius: 5px;
                        /* Rounded corners */
                        padding: 10px;
                        /* Padding inside the border */
                        margin-bottom: 15px;
                        /* Space below each file upload field */
                    }
                </style>

                @include('sifa.sifa-registration.forms.sifadocument')

                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function() {
        $('#employee').on('change', function() {
            $.ajax({
                url: '/getemployeebyid/' + $(this).val(),
                type: 'GET',
                success: function(response) {
                    console.log(response)
                    $('#personal-info').show();
                   
                    $('#emp_gender').text(response.gender); // Set gender
                    $('#emp_dob').text(response.dob); // Set DOB
                    $('#emp_cid').text(response.cid_no); 
                    $('#emp_marital_status').text(response.marital_status); // Set MaritialStatus
                    $('#emp_email').text(response.email); 
                    $('#emp_contact_number').text(response.contact_number); 
                    $('#emp_dzongkhang').text(response.dzongkhag); 
                    $('#emp_Gewog').text(response.gewog); 
                    $('#emp_village').text(response.village); 
                },
                error: function() {
                    alert('An error occurred while processing your request');
                }
            });
        });
    })
</script>
@endpush