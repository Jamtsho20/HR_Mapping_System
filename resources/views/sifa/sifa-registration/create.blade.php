@extends('layouts.app')
@section('page-title', 'SIFA Registration')
@section('content')

<form action="{{ route('sifa-registration.store') }}" method="POST" class="button-control" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="employee_id" value="{{ auth()->id() }}">
            <input type="hidden" name="status" id="status" value="1">
            @include('sifa.sifa-registration.forms.personalinfo')
            <hr>
            <!-- Membership for SIFA Section with Radio Buttons -->
            <h5 class="mb-4"><strong>Do you wish to register as a member of SIFA?</strong></h5>
            <div class="form-group">
                <label class="mr-3">
                    <input type="radio" name="is_registered" value="yes" required>
                    <span><strong>YES: </strong><em>(I wish to become a member of Staff Initiative for Financial Assistance (SIFA) by declaring and confirming that I have read and have been briefed on the By-laws for SIFA. I have clearly understood and agree to the By-laws of SIFA. In addition, I give my consent to the company to store and use my information I will submit for the purposes related to SIFA.)</em></span>
                </label>
                <label>
                    <input type="radio" name="is_registered" value="no" required>
                    <span><strong>NO: </strong><em>(I confirm NOT to become a member of Staff Initiative for Financial Assistance (SIFA) by declaring and confirming that I have read and have been briefed on the By-laws for SIFA. I have clearly understood the By-laws of SIFA and do not wish to become a member.)</em></span>
                </label>
            </div>
            <hr>

            <!-- Dynamic Sections based on Membership Choice -->
            <div id="sifa-sections" style="display: none;">
                @include('sifa.sifa-registration.forms.sifanomination')
                <hr>
                @include('sifa.sifa-registration.forms.sifadependent')
                <hr>
                @include('sifa.sifa-registration.forms.sifadocument')
            </div>

            <!-- Remarks for 'NO' selection -->
            <div id="remarks-section" style="display: none;">
                <div class="form-group">
                    <label for="remarks">Remarks:<span class="text-danger">*</span></label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="4"></textarea>
                </div>
            </div>

            <hr>
            <div class="form-group d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the radio buttons for SIFA membership
        const yesRadio = document.querySelector('input[name="is_registered"][value="yes"]');
        const noRadio = document.querySelector('input[name="is_registered"][value="no"]');


        // Get the sections to show/hide
        const sifaSections = document.getElementById('sifa-sections');
        const remarksSection = document.getElementById('remarks-section');

        // Event listener for "YES" option
        yesRadio.addEventListener('change', function() {
            sifaSections.style.display = 'block'; // Show the sections
            remarksSection.style.display = 'none'; // Hide remarks section
        });

        // Event listener for "NO" option
        noRadio.addEventListener('change', function() {
            sifaSections.style.display = 'none'; // Hide the sections
            remarksSection.style.display = 'block'; // Show remarks section
        });

        // Initialize state based on the selected radio button
        if (yesRadio.checked) {
            sifaSections.style.display = 'block';
            remarksSection.style.display = 'none';
        } else if (noRadio.checked) {
            sifaSections.style.display = 'none';
            remarksSection.style.display = 'block';
        }
    });
</script>