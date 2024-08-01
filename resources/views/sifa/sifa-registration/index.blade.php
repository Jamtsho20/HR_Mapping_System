@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<style>
    .form-border {
        border: 2px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
    }
    .hidden {
        display: none;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <div class="form-group form-border">
                <form id="sifa-form" action="{{ route('sifa-registration.create') }}" method="GET">
                    <label><strong>Membership for SIFA is purely voluntary. Do you wish to register as a member of SIFA?</strong></label>
                    <br><br>
                    <div class="form-check">
                        <input type="radio" id="yes" name="sifa_registration" value="1" class="form-check-input">
                        <label for="yes" class="form-check-label"><strong>Yes</strong> <small><i>(If you wish to register as a member, you cannot withdraw your membership from SIFA for the entire duration of your service with the company)</i></small></label>
                    </div>
                    <br>
                    <div class="form-check">
                        <input type="radio" id="no" name="sifa_registration" value="0" class="form-check-input">
                        <label for="no" class="form-check-label"><strong>No</strong> <small><i>(If you do not wish to register as a member this time, you cannot become a member for the entire duration of your service with the company)<i></small></label>
                    </div>
                    <br>
                    <input type="hidden" id="selected-option" name="selected_option">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>   
        </div>
    </div>
</div>

<div class="container mt-5 hidden" id="no-form-container">
    <!-- "No" form for declining reason -->
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="decline-reason"><strong>Please provide your reason for declining SIFA membership:</strong></label>
                    <textarea class="form-control form-control-sm" id="decline-reason" name="decline_reason" rows="4" required></textarea>
                </div>
                <hr>
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('sifa-form');
    const yesRadio = document.getElementById('yes');
    const noRadio = document.getElementById('no');
    const selectedOption = document.getElementById('selected-option');
    const noFormContainer = document.getElementById('no-form-container');

    form.addEventListener('submit', function (e) {
        if (noRadio.checked) {
            e.preventDefault(); // Prevent form from submitting
            selectedOption.value = "0";
            noFormContainer.classList.remove('hidden');
        } else if (yesRadio.checked) {
            selectedOption.value = "1";
        }
    });
});
</script>

@endsection
