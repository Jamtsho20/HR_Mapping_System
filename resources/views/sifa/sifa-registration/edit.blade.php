@extends('layouts.app')
@section('page-title', 'Edit SIFA Registration')
@section('content')

<form action="{{ route('sifa-registration.update', $sifaRegistration->id) }}" method="POST" class="button-control" enctype="multipart/form-data">
    @csrf
    @method('PUT')
     <div class="card">
        <div class="card-body">
            <div class="row">
            <input type="hidden" name="employee_id" value="{{ auth()->id() }}">
            <!-- Personal Information Section -->
            @include('sifa.sifa-registration.forms.personalinfo')
            <hr>
            <!-- Sifa Nomination Section -->
            @include('sifa.sifa-registration.edit.sifanomination')
            <hr>
            <!-- Sifa Dependent Section -->
            @include('sifa.sifa-registration.edit.sifadependent')
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
            <hr>
            <!-- Sifa Documents Section -->
            @include('sifa.sifa-registration.edit.sifadocument')
        </div>
        <!-- Submit Button -->
        <div class="form-group d-flex justify-content-center">
            <button type="submit" class="btn btn-primary me-3">Update</button>
            <a href="{{ route('sifa-registration.index') }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush