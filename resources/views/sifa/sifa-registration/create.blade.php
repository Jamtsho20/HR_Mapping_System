@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<div class="container mt-5">
    <form action="" method="POST" enctype="multipart/form-data" class="button-control">
    @csrf
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Basic Employee Information</h5>
        </div>
        <div class="card-body">
            @include('sifa.sifa-registration.forms.personalinfo')
            <hr>
            @include('sifa.sifa-registration.forms.permanent')
            <hr>
            @include('sifa.sifa-registration.forms.professional')
            <hr>
            @include('sifa.sifa-registration.forms.sifanomination')
            <hr>
            @include('sifa.sifa-registration.forms.sifadependent')
            <style>
                .file-upload-border {
                    border: 1px solid #ccc; /* Light grey border */
                    border-radius: 5px; /* Rounded corners */
                    padding: 10px; /* Padding inside the border */
                    margin-bottom: 15px; /* Space below each file upload field */
                }
            </style>
            @include('sifa.sifa-registration.forms.sifadocument')
        </div>  
        <hr>
            <div class="form-group d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection
