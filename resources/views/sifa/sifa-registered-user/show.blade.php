@extends('layouts.app')
@section('page-title', 'Sifa Registration Details')
@section('buttons')
<a href="{{ route('sifa-registered-user.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Sifa Registered List</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <!-- Personal Information Section -->
        @include('sifa.sifa-registration.forms.personalinfo')
        <hr>
        <!-- Sifa Nomination Section -->
        @include('sifa.sifa-registration.show.sifanomination')
        <hr>
        <!-- Sifa Dependent Section -->
        @include('sifa.sifa-registration.show.sifadependent')
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
        @include('sifa.sifa-registration.show.sifadocument')
    </div>
    <div class="card-footer">
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <Strong>Approved By</strong>
                <span class="pull-right">{{$sifaRegistration->status == 3 ?$sifaRegistration->sifa_approved_by->name:'N/A'}}</span>
            </li>
            <li class="list-group-item">
                <strong>Rejected By</strong>
                <span class="pull-right">{{$sifaRegistration->status == -1 ?$sifaRegistration->sifa_approved_by->name:'N/A'}}</span>
            </li>
        </ul>
    </div>
</div>

@endsection

@push('page_scripts')
@endpush