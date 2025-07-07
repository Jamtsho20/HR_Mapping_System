@extends('layouts.app')
@section('page-title', 'Sifa Registration Details')
@section('buttons')
<a href="{{ route('sifa-registered-user.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Sifa Approval List</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        @if ($sifaRegistration && $sifaRegistration->is_registered == 1)
        <!-- Personal Information Section -->
        @include('sifa.sifa-registration.forms.personalinfo')
        <hr>
        <div class="form-group form-check mt-4">
            <input type="checkbox" class="form-check-input" id="agree" checked disabled>
            <label class="form-check-label" for="agree">
                I declare that I have fully read, understood and agree to the <strong>By-laws of SIFA</strong>.
            </label>
        </div>
        <!-- Sifa Nomination Section -->
        @include('sifa.sifa-registration.show.sifanomination')
        <hr>
        <!-- Sifa Dependent Section -->
        @include('sifa.sifa-registration.show.sifadependent')
        <hr>
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
        <!-- Sifa Documents Section -->
        @include('sifa.sifa-registration.show.sifadocument')
        @else
        <div class="card mt-3">
            <div class="card-body">
                <p class="text-center text-danger">
                    The employee has not opted for SIFA Registration.
                </p>
            </div>
            @include('sifa.sifa-registration.forms.personalinfo')

            <!-- Sifa Retirement Nominations Section -->
            @include('sifa.sifa-registration.show.sifaretirementnomination')
        </div>
        @endif
    </div>

</div>

@endsection

@push('page_scripts')
@endpush