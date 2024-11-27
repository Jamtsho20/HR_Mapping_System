@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<div class="container mt-5">
    <form action="{{ route('sifa-registration.store') }}" method="POST" class="button-control" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="employee_id" value="{{ auth()->id() }}">

                @include('sifa.sifa-registration.forms.personalinfo')
                <hr>

                <!-- Membership for SIFA Section with Radio Buttons -->
                <h5 class="mb-4"><strong>Membership for SIFA is purely voluntary. Do you wish to register as a member of SIFA?</strong></h5>
                <div class="form-group">
                    <label class="mr-3">
                        <input type="radio" name="is_registered" value="yes" required> 
                        <span><strong>YES: </strong><em>(If you wish to register as a member, you cannot withdraw your membership from SIFA for the entire duration of your service with the company.)</em></span>
                    </label>
                    <label>
                        <input type="radio" name="is_registered" value="no" required> 
                        <span><strong>NO: </strong><em>(If you do not wish to register as a member this time, you cannot become a member for the entire duration of your service with the company.)</em></span>
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
                        <label for="remark">Remarks:<span class="text-danger">*</span></label>
                        <textarea name="remark" id="remark" class="form-control" rows="4"></textarea>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

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
        sifaSections.style.display = 'block';  // Show the sections
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
